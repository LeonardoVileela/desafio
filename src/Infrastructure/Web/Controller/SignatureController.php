<?php

namespace Infrastructure\Web\Controller;


use Core\Application\Service\SignatureService;

class SignatureController
{
    private SignatureService $signatureService;

    public function __construct()
    {
        $this->signatureService = new SignatureService();
    }

    public function showUpload(): void
    {
        require_once __DIR__ . '/../View/signature/upload.php';
    }

    public function upload(): void
    {
        $documentId = $this->signatureService->upload();
        if ($documentId) {
            header('Location: ' . BASE_URL . '/assinatura/signatarios/' . $documentId);
        } else {
            $errorMsg = "Tivemos um problema no envio do arquivo.";
            require_once __DIR__ . '/../View/signature/upload.php';
        }
    }

    public function showSigners(): void
    {
        require_once __DIR__ . '/../View/signature/signers.php';
    }

    public function saveSigners($data): void
    {
        $result = $this->signatureService->saveSigners($data);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Usuário adicionado com sucesso.', 'id' => $result]);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falha ao adicionar usuário.']);
            exit();
        }
    }

    public function showDocuments(): void
    {
        $documents = $this->signatureService->getAllDocuments();
        require_once __DIR__ . '/../View/signature/documents.php';
    }

    public function showSignature($signatureId): void
    {
        $signature = $this->signatureService->getSignature($signatureId);
        $attendees = $this->signatureService->getAllAttendees($signatureId);
        require_once __DIR__ . '/../View/signature/signature.php';
    }

    public function download($chave): void
    {
        $this->signatureService->download($chave);
    }

    public function getSignatures($chave)
    {
        $result = $this->signatureService->getSignatures($chave);
        if ($result) {
            echo json_encode($result);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falha ao buscar.']);
            exit();
        }

    }

    public function showAddSignature($signatureId)
    {
        require_once __DIR__ . '/../View/signature/addSigner.php';
    }

    public function addSigners($signatureId, $data)
    {
        $result = $this->signatureService->addSigners($signatureId, $data);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Novos signatários adicionados com sucesso.', 'id' => $signatureId]);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falha ao adicionar signatários.']);
            exit();
        }

    }

    public function sendEmail($signatureId, $data)
    {
        $result = $this->signatureService->sendEmail($data);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'E-mail enviado com sucesso.', 'id' => $signatureId]);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falha ao enviar o e-mail.']);
            exit();
        }
    }

    public function deleteSigner($signatureId, $data)
    {
        $result = $this->signatureService->deleteSigner($data);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Signatário deletado com sucesso.', 'id' => $signatureId]);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falha ao deletar signatário.']);
            exit();
        }
    }

    public function deleteDocument($signatureId): void
    {
        $result = $this->signatureService->deleteDocument($signatureId);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Documento deletado com sucesso.', 'id' => $signatureId]);
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Falha ao deletar documento.']);
            exit();
        }
    }
}
