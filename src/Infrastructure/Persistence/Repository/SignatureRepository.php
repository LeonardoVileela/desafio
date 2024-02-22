<?php

namespace Infrastructure\Persistence\Repository;

use Exception;
use Infrastructure\Persistence\DatabaseConnection;
use PDO;
use PDOException;
use stdClass;

class SignatureRepository
{

    private $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getConnection();
    }

    public function insertDocument($document): false|string
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO documents (name, uploadId) VALUES (:name, :uploadId)");

            $stmt->bindParam(':name', $document->name);
            $stmt->bindParam(':uploadId', $document->uploadId);


            if ($stmt->execute()) {
                return $this->connection->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }

    }

    public function findDocument($id): ?stdClass
    {
        $stmt = $this->connection->prepare("SELECT * FROM documents WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $document = $stmt->fetch(PDO::FETCH_OBJ);

        return $document ?: null;
    }

    public function insertSignatures($signature): false|string
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO signatures (id, document_id, chave, signUrl, inProcessing) VALUES (:id, :document_id, :chave, :signUrl, :inProcessing);");

            $stmt->bindParam(':id', $signature->id);
            $stmt->bindParam(':document_id', $signature->document_id);
            $stmt->bindParam(':chave', $signature->chave);
            $stmt->bindParam(':signUrl', $signature->signUrl);
            $stmt->bindParam(':inProcessing', $signature->inProcessing);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function createAttendee($attendee, $signature_id)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO attendees (signature_id, signerId, name, email, individualIdentificationCode, orderAt, action, signUrl, batchSignUrl) VALUES ( :signature_id, :signerId, :name, :email, :individualIdentificationCode, :order, :action, :signUrl, :batchSignUrl);");

            $stmt->bindParam(':signature_id', $signature_id);
            $stmt->bindParam(':signerId', $attendee->signerId);
            $stmt->bindParam(':name', $attendee->name);
            $stmt->bindParam(':email', $attendee->email);
            $stmt->bindParam(':individualIdentificationCode', $attendee->individualIdentificationCode);
            $stmt->bindParam(':order', $attendee->order);
            $stmt->bindParam(':action', $attendee->action);
            $stmt->bindParam(':signUrl', $attendee->signUrl);
            $attendee->batchSignUrl = $attendee->batchSignUrl ?? "";
            $stmt->bindParam(':batchSignUrl', $attendee->batchSignUrl);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function addTitle($documentId, $documentTitle)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE documents SET title = :title WHERE documents.id = :id;");

            $stmt->bindParam(':title', $documentTitle);
            $stmt->bindParam(':id', $documentId);

            $stmt->execute();

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllDocuments()
    {

        $stmt = $this->connection->prepare("SELECT s.chave AS chave, s.id AS signatureId, d.name AS documentName, d.title AS documentTitle, d.id AS documentId
    FROM signatures s
    JOIN documents d ON s.document_id = d.id");

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $documents = [];

        foreach ($data as $row) {
            $document = new stdClass();
            $document->id = $row['documentId'];
            $document->title = $row['documentTitle'];
            $document->name = $row['documentName'];
            $document->chave = $row['chave'];
            $document->signatureId = $row['signatureId'];
            $documents[] = $document;
        }

        return $documents;

    }

    public function getAllAttendees($signatureId): ?array
    {

        $stmt = $this->connection->prepare("SELECT * FROM attendees WHERE signature_id = :signatureId AND active = 1 ORDER BY orderAt;");

        $stmt->execute(['signatureId' => $signatureId]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $attendees = [];

        foreach ($data as $row) {
            $attendee = new stdClass();
            $attendee->signerId = $row['signerId'];
            $attendee->name = $row['name'];
            $attendee->email = $row['email'];
            $attendee->individualIdentificationCode = $row['individualIdentificationCode'];
            $attendee->action = $row['action'];
            $attendee->signUrl = $row['signUrl'];
            $attendees[] = $attendee;
        }

        return $attendees;
    }

    public function getSignature($signatureId): ?stdClass
    {
        $stmt = $this->connection->prepare("SELECT s.*, d.name AS documentName, d.title AS documentTitle 
    FROM signatures s
    JOIN documents d ON s.document_id = d.id
    WHERE s.id = :signatureId;");
        $stmt->execute(['signatureId' => $signatureId]);

        $data = $stmt->fetch();

        if (!$data) return null;

        $signature = new stdClass();
        $signature->id = $data['id'];
        $signature->chave = $data['chave'];
        $signature->signUrl = $data['signUrl'];
        $signature->inProcessing = $data['inProcessing'];
        $signature->documentName = $data['documentName'];
        $signature->documentTitle = $data['documentTitle'];

        return $signature;
    }

    public function getChave($signatureId)
    {
        $stmt = $this->connection->prepare("SELECT s.chave FROM signatures s WHERE s.id = :id;");
        $stmt->execute(['id' => $signatureId]);

        $data = $stmt->fetch();

        if (!$data) return null;

        return $data['chave'];
    }

    public function getOrder($signatureId)
    {
        $stmt = $this->connection->prepare("SELECT MAX(orderAt) + 1 AS next_orderAt FROM attendees WHERE signature_id = :id;");
        $stmt->execute(['id' => $signatureId]);

        $data = $stmt->fetch();

        if (!$data) return null;

        return $data['next_orderAt'];
    }

    public function deleteSigner($signerId): bool
    {
        try {
            $stmt = $this->connection->prepare("UPDATE attendees SET active = 0 WHERE signerId = :id;");

            $stmt->bindParam(':id', $signerId);

            $stmt->execute();

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }

    }

    public function deleteDocument($signatureId): bool
    {
        try {
            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare("DELETE FROM signatures WHERE id = :id");
            $stmt->execute(['id' => $signatureId]);

            if ($stmt->rowCount() > 0) {
                $this->connection->commit();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->connection->rollBack();
            return false;
        }

    }
}