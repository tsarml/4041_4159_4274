
--V1
CREATE DATABASE IF NOT EXISTS 4041_4159_4274;
USE 4041_4159_4274;

CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    region VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    type_besoin ENUM('nature','materiau','argent') NOT NULL,
    description VARCHAR(255) NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES ville(id) ON DELETE CASCADE,
    INDEX idx_ville (ville_id)
);

CREATE TABLE don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donateur VARCHAR(100) NOT NULL,
    type_don ENUM('nature','materiau','argent') NOT NULL,
    description VARCHAR(255) NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    unite VARCHAR(20) NOT NULL,
    ville_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES ville(id) ON DELETE SET NULL,
    INDEX idx_ville (ville_id)
);

CREATE TABLE attribution (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    don_id INT NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE,
    FOREIGN KEY (don_id) REFERENCES don(id) ON DELETE CASCADE,
    INDEX idx_besoin (besoin_id),
    INDEX idx_don (don_id)
);

DELIMITER //
CREATE TRIGGER validate_unite_before_insert
BEFORE INSERT ON attribution
FOR EACH ROW
BEGIN
    DECLARE besoin_unite VARCHAR(20);
    DECLARE don_unite VARCHAR(20);
    
    SELECT unite INTO besoin_unite FROM besoin WHERE id = NEW.besoin_id;
    SELECT unite INTO don_unite FROM don WHERE id = NEW.don_id;
    
    IF besoin_unite != don_unite THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Les unités du besoin et du don doivent correspondre';
    END IF;
END//
DELIMITER ;

INSERT INTO ville (nom, region) VALUES
('Antananarivo', 'Analamanga'),
('Toamasina', 'Atsinanana'),
('Fianarantsoa', 'Haute Matsiatra'),
('Mahajanga', 'Boeny');

INSERT INTO besoin (ville_id, type_besoin, description, quantite, unite) VALUES
(1, 'nature', 'Riz blanc', 500, 'kg'),
(1, 'nature', 'Huile végétale', 100, 'litre'),
(1, 'argent', 'Fonds urgence', 1000000, 'Ar'),
(2, 'nature', 'Riz blanc', 800, 'kg'),
(2, 'materiau', 'Tôle ondulée', 150, 'feuille'),
(3, 'materiau', 'Clous', 50, 'kg'),
(4, 'nature', 'Eau potable', 500, 'litre');

INSERT INTO don (donateur, type_don, description, quantite, unite) VALUES
('Croix-Rouge Madagascar', 'nature', 'Riz blanc', 2000, 'kg'),
('Croix-Rouge Madagascar', 'nature', 'Huile végétale', 500, 'litre'),
('Ministère des Finances', 'argent', 'Fonds urgence', 5000000, 'Ar'),
('ONG Tafita', 'materiau', 'Tôle ondulée', 300, 'feuille'),
('Anonyme', 'nature', 'Eau potable', 1000, 'litre');

INSERT INTO attribution (besoin_id, don_id, quantite) VALUES
(1, 1, 200),
(2, 2, 50),
(3, 3, 500000),
(4, 1, 300),
(5, 4, 80);


--V2

ALTER TABLE besoin ADD COLUMN prix_unitaire DECIMAL(12,2) NULL AFTER unite;

CREATE TABLE achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    montant_total DECIMAL(12,2) NOT NULL,
    date_achat DATE NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES ville(id) ON DELETE CASCADE,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE
);


ALTER TABLE achat ADD COLUMN don_id INT NOT NULL AFTER besoin_id;
ALTER TABLE achat ADD FOREIGN KEY (don_id) REFERENCES don(id) ON DELETE CASCADE;


UPDATE besoin SET prix_unitaire = 4500 WHERE type_besoin = 'nature' AND description LIKE '%Riz%';
UPDATE besoin SET prix_unitaire = 8000 WHERE type_besoin = 'nature' AND description LIKE '%Huile%';
UPDATE besoin SET prix_unitaire = 500 WHERE type_besoin = 'nature' AND description LIKE '%Eau%';
UPDATE besoin SET prix_unitaire = 15000 WHERE type_besoin = 'materiau' AND description LIKE '%Tôle%';
UPDATE besoin SET prix_unitaire = 3000 WHERE type_besoin = 'materiau' AND description LIKE '%Clou%';



--V3

ALTER TABLE `don` ADD COLUMN IF NOT EXISTS `valeur_unitaire` DECIMAL(12,2) DEFAULT NULL AFTER `unite`;

CREATE TABLE IF NOT EXISTS `vente` (
    `id`              INT AUTO_INCREMENT PRIMARY KEY,
    `don_id`          INT           NOT NULL,
    `quantite`        DECIMAL(12,2) NOT NULL,
    `valeur_unitaire` DECIMAL(12,2) NOT NULL,
    `remise_pct`      DECIMAL(5,2)  DEFAULT 0,
    `montant_total`   DECIMAL(14,2) NOT NULL,
    `description`     VARCHAR(255)  DEFAULT NULL,
    `created_at`      DATETIME      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`don_id`) REFERENCES `don`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

UPDATE `don` SET `valeur_unitaire` = 4500  WHERE `description` LIKE '%Riz%';
UPDATE `don` SET `valeur_unitaire` = 8000  WHERE `description` LIKE '%Huile%';
UPDATE `don` SET `valeur_unitaire` = 15000 WHERE `description` LIKE '%T%le%';
UPDATE `don` SET `valeur_unitaire` = 3000  WHERE `description` LIKE '%Clou%';
UPDATE `don` SET `valeur_unitaire` = 500   WHERE `description` LIKE '%Eau%';
UPDATE `don` SET `valeur_unitaire` = 1     WHERE `type_don` = 'argent' AND `valeur_unitaire` IS NULL;