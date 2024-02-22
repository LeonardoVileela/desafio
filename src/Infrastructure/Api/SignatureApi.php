<?php

namespace Infrastructure\Api;

class SignatureApi
{
    public function upload($fileName, $base64PdfContent): bool|string
    {
        $url = 'https://api-sbx.portaldeassinaturas.com.br/api/v2/document/upload';

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        $jsonPayload = json_encode([
            "fileName" => $fileName,
            "bytes" => $base64PdfContent,
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $response;
        }
    }

    public function create($data): bool|string
    {
        $url = 'https://api-sbx.portaldeassinaturas.com.br/api/v2/document/create';

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        $jsonPayload = json_encode($data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $response;
        }
    }

    public function package($chave): bool|string
    {
        $url = "https://api-sbx.portaldeassinaturas.com.br/api/v2/document/package?key=$chave&includeOriginal=true&includeManifest=true&zipped=true";

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $response;
        }
    }


    public function validateSignatures($chave): bool|string
    {
        $url = "https://api-sbx.portaldeassinaturas.com.br/api/v2/document/ValidateSignatures?key=$chave";

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $response;
        }
    }

    public function participantAdd($data): bool|string
    {
        $url = 'https://api-sbx.portaldeassinaturas.com.br/api/v2/document/participantAdd';

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        $jsonPayload = json_encode($data);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $response;
        }
    }

    public function sendEmail($data): bool
    {
        $url = 'https://api-sbx.portaldeassinaturas.com.br/api/v2/document/SendReminder';

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        $jsonPayload = json_encode($data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return true;
        }
    }

    public function deleteSigner($data): bool
    {
        $url = 'https://api-sbx.portaldeassinaturas.com.br/api/v2/document/participantDiscard';

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        $jsonPayload = json_encode($data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return true;
        }
    }

    public function deleteDocument($signatureId): bool
    {
        $url = "https://api-sbx.portaldeassinaturas.com.br/api/v2/document/delete/$signatureId";

        $subscriptionKey = API_TOKEN;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Token: $subscriptionKey",
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return true;
        }

    }

}