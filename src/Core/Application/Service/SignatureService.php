<?php

namespace Core\Application\Service;

use Infrastructure\Api\SignatureApi;
use Infrastructure\Persistence\Repository\SignatureRepository;
use stdClass;

class SignatureService
{
    private SignatureRepository $signatureRepository;

    private SignatureApi $signatureApi;

    private UtilService $utilService;

    public function __construct()
    {
        $this->signatureRepository = new SignatureRepository();
        $this->signatureApi = new SignatureApi();
        $this->utilService = new UtilService();
    }

    public function getAllDocuments(): ?array
    {
        return $this->signatureRepository->getAllDocuments();
    }

    public function upload(): false|string
    {
        if (isset($_FILES['document'])) {
            $file = $_FILES['document'];
            $fileName = $file['name'];
            $pdfContent = file_get_contents($file['tmp_name']);
            $base64PdfContent = base64_encode($pdfContent);

            $response = $this->signatureApi->upload($fileName, $base64PdfContent);
            if (!$response) {
                return false;
            }

            $responseObject = json_decode($response);

            $document = new stdClass();
            $document->uploadId = $responseObject->uploadId;
            $document->name = $fileName;

            $responseId = $this->signatureRepository->insertDocument($document);

            if ($responseId) {
                return $responseId;
            } else {
                return false;
            }
        }

        return false;
    }

    public function saveSigners($data)
    {
        $uploadFind = $this->signatureRepository->findDocument($data["uploadId"]);
        $this->signatureRepository->addTitle($data["uploadId"], $data["documentTitle"]);

        $body = [];

        $document = [];
        $document["name"] = $data["documentTitle"];

        $upload = [];
        $upload["id"] = $uploadFind->uploadId;
        $upload["name"] = $uploadFind->name;
        $document["upload"] = $upload;

        $body["document"] = $document;

        $sender = $data["sender"];
        $sender["individualIdentificationCode"] = null;
        $body["sender"] = $sender;

        if (!empty($data["signers"])) {
            $singners = $data["signers"];
            $body["signers"] = $singners;
        }

        if (!empty($data["electronicSigners"])) {
            $electronicSigners = $data["electronicSigners"];
            $body["electronicSigners"] = $electronicSigners;
        }

        $response = $this->signatureApi->create($body);

        if (!$response) {
            return false;
        }

        $responseObject = json_decode($response);
        $responseObject->document_id = $data["uploadId"];

        if (!$this->signatureRepository->insertSignatures($responseObject)) {
            return false;
        }

        foreach ($responseObject->attendees as $attendee) {
            if (!$this->signatureRepository->createAttendee($attendee, $responseObject->id)) {
                return false;
            }
        }

        return $responseObject->id;
    }

    public function getSignature($signatureId): ?stdClass
    {
        return $this->signatureRepository->getSignature($signatureId);
    }

    public function getAllAttendees($signatureId): ?array
    {
        return $this->signatureRepository->getAllAttendees($signatureId);
    }

    public function download($chave)
    {
        $responseZip = $this->signatureApi->package($chave);


        $response = json_decode($responseZip, true);

        $fileContent = base64_decode($response['bytes']);

        $fileName = $response['name'];

        $tempFilePath = sys_get_temp_dir() . '/' . $fileName;

        file_put_contents($tempFilePath, $fileContent);

        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($tempFilePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tempFilePath));

        ob_clean();
        flush();

        readfile($tempFilePath);

        unlink($tempFilePath);

        exit;
    }

    public function getSignatures($chave): stdClass
    {
        $response = $this->signatureApi->validateSignatures($chave);

        $response = json_decode($response, true);

        $signatures = new stdClass();

        $digitalSignatures = [];

        foreach ($response["signatures"] as $signature) {
            $digitalSignature = new stdClass();
            $digitalSignature->date = $signature["date"];
            $digitalSignature->cnpj = $signature["cnpj"];
            $digitalSignature->company = $signature["company"];
            $digitalSignatures[] = $digitalSignature;
        }

        $electronicSignatures = [];

        foreach ($response["electronicSignatures"] as $signature) {
            $electronicSignature = new stdClass();
            $electronicSignature->date = $signature["date"];
            $electronicSignature->identifier = $signature["identifier"];
            $electronicSignature->user = $signature["user"];
            $electronicSignatures[] = $electronicSignature;
        }

        $signatures->digitalSignatures = $digitalSignatures;
        $signatures->electronicSignatures = $electronicSignatures;

        return $signatures;

    }

    public function addSigners($signatureId, $data): bool
    {
        $body = [];

        $body["documentId"] = $signatureId;


        if (!empty($data["signers"])) {
            $singners = $data["signers"];
            $body["signers"] = $singners;
        }

        if (!empty($data["electronicSigners"])) {
            $electronicSigners = $data["electronicSigners"];
            $body["electronicSigners"] = $electronicSigners;
        }

        $order = $this->signatureRepository->getOrder($signatureId);

        if (isset($body['electronicSigners']) && is_array($body['electronicSigners'])) {
            foreach ($body['electronicSigners'] as &$signer) {
                $signer['step'] = $order++;
            }
        }

        if (isset($body['signers']) && is_array($body['signers'])) {
            foreach ($body['signers'] as &$signer) {
                $signer['step'] = $order++;
            }
        }


        $response = $this->signatureApi->participantAdd($body);

        if (!$response) {
            return false;
        }

        $responseObject = json_decode($response);
        $attendees = [];

        $order = $this->signatureRepository->getOrder($signatureId);

        if (isset($responseObject->signers) && is_array($responseObject->signers)) {
            foreach ($responseObject->signers as $signer) {
                $attendees[] = [
                    "id" => $signer->id,
                    "name" => $signer->name,
                    "email" => $signer->email,
                    "individualIdentificationCode" => $signer->individualIdentificationCode,
                    "order" => $order++,
                    "action" => "DIGITAL-SIGNATURE",
                    "signUrl" => "email"
                ];
            }
        }

        if (isset($responseObject->electronicSigners) && is_array($responseObject->electronicSigners)) {
            foreach ($responseObject->electronicSigners as $eSigner) {
                $attendees[] = [
                    "id" => $eSigner->id,
                    "name" => $eSigner->name,
                    "email" => $eSigner->email,
                    "individualIdentificationCode" => $eSigner->individualIdentificationCode,
                    "order" => $order++,
                    "action" => "ELETRONIC-SIGNATURE",
                    "signUrl" => "email"
                ];
            }
        }


        foreach ($attendees as $attendee) {
            $attendeeObject = $this->utilService->convertArrayToObject($attendee);
            $attendeeObject->signerId = $attendeeObject->id;
            if (!$this->signatureRepository->createAttendee($attendeeObject, $signatureId)) {
                return false;
            }
        }

        return true;
    }

    public function sendEmail($data): bool
    {
        return $this->signatureApi->sendEmail($data);
    }

    public function deleteSigner($data): bool
    {
        if ($this->signatureApi->deleteSigner($data)) {
            if ($this->signatureRepository->deleteSigner($data["stageFlowId"])) {
                return true;
            }
        }
        return false;
    }

    public function deleteDocument($signatureId): bool
    {
        if ($this->signatureApi->deleteDocument($signatureId)) {

            if ($this->signatureRepository->deleteDocument($signatureId)) {
                return true;
            }
        }
        return false;
    }

}