-- Table pour les cartes de visite
CREATE TABLE IF NOT EXISTS business_cards (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque carte de visite
    first_name VARCHAR(255) NOT NULL, -- Prénom du titulaire de la carte
    last_name VARCHAR(255) NOT NULL, -- Nom de famille du titulaire de la carte
    company VARCHAR(255) NOT NULL, -- Nom de l'entreprise
    job_title VARCHAR(255), -- Intitulé du poste
    competence VARCHAR(255), -- Compétence principale
    domain VARCHAR(255), -- Domaine d'expertise
    email VARCHAR(255) NOT NULL, -- Adresse email
    phone VARCHAR(20), -- Numéro de téléphone
    address TEXT, -- Adresse complète
    slogan VARCHAR(255), -- Slogan ou accroche
    details TEXT, -- Détails supplémentaires
    photo BLOB, -- Photo de la carte de visite (stockée sous forme de BLOB)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création de la carte de visite
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Date de mise à jour de la carte de visite
);

-- Index pour accélérer les recherches par email
CREATE INDEX IF NOT EXISTS idx_business_cards_email ON business_cards (email);
