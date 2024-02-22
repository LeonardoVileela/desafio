CREATE TABLE users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



CREATE TABLE documents
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    title    VARCHAR(255),
    name     VARCHAR(255) NOT NULL,
    uploadId VARCHAR(255) NOT NULL
);

CREATE TABLE signatures
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    document_id  INT,
    chave        VARCHAR(255) NOT NULL,
    signUrl      VARCHAR(255) NOT NULL,
    inProcessing BOOLEAN      NOT NULL,
    FOREIGN KEY (document_id) REFERENCES documents (id) ON DELETE CASCADE
);

CREATE TABLE attendees
(
    id                           INT AUTO_INCREMENT PRIMARY KEY,
    signature_id                 INT,
    signerId                     INT          NOT NULL,
    name                         VARCHAR(255) NOT NULL,
    email                        VARCHAR(255) NOT NULL,
    individualIdentificationCode VARCHAR(255),
    orderAt                      INT          NOT NULL,
    action                       VARCHAR(255) NOT NULL,
    signUrl                      VARCHAR(255) NOT NULL,
    batchSignUrl                 VARCHAR(255) DEFAULT '',
    active                       TINYINT(1)   NOT NULL DEFAULT 1,
    FOREIGN KEY (signature_id) REFERENCES signatures (id) ON DELETE CASCADE
);



DELIMITER
$$

CREATE TRIGGER before_signature_delete
    BEFORE DELETE
    ON signatures
    FOR EACH ROW
BEGIN
    DECLARE remaining_signatures INT;

    SELECT COUNT(*)
    INTO remaining_signatures
    FROM signatures
    WHERE document_id = OLD.document_id;

    IF remaining_signatures = 1 THEN
    DELETE FROM documents WHERE id = OLD.document_id;
END IF;
END$$

DELIMITER ;

