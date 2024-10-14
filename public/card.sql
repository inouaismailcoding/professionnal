-- Table pour les utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque utilisateur
    username VARCHAR(50) UNIQUE NOT NULL, -- Nom d'utilisateur unique
    password VARCHAR(255) NOT NULL, -- Hash du mot de passe (pour la sécurité)
    email VARCHAR(100), -- Adresse e-mail de l'utilisateur
    role ENUM('admin', 'manager', 'staff') DEFAULT 'staff', -- Rôle de l'utilisateur (admin, manager, staff)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date et heure de création du compte
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Date et heure de la dernière mise à jour du compte
);

-- Table pour les profils des  utilisateurs
CREATE TABLE IF NOT EXISTS user_profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,   -- Unique identifier for each record
    user_id INT, -- Référence a l'utilisateur
    first_name TEXT NOT NULL,               -- The first name of the card owner
    last_name TEXT NOT NULL,                -- The last name of the card owner
    date_born DATE NULL,
    FOREIGN KEY (user_id) REFERENCES users(id), -- Clé étrangère vers la table des produits
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date et heure de création du compte
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Date et heure de la dernière mise à jour du compte
);


-- Table: business_card
-- This table stores the details entered through the business card creation form

CREATE TABLE IF NOT EXISTS business_cards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,   -- Unique identifier for each record
    user_id INT, -- Référence a l'utilisateur
    first_name TEXT NOT NULL,               -- The first name of the card owner
    last_name TEXT NOT NULL,                -- The last name of the card owner
    companie TEXT,                          -- The company name, if applicable
    competence TEXT,                        -- The competence or skills of the card owner
    domaine TEXT,                           -- The business domain or industry
    email TEXT NOT NULL,                    -- The email of the card owner, stored as text
    phone TEXT NOT NULL,                    -- The phone number (text allows for various formats)
    address TEXT,                           -- The address of the card owner
    slogan TEXT,                            -- A custom slogan or tagline
    details TEXT,                           -- Any additional details, stored as a text block
    FOREIGN KEY (user_id) REFERENCES users(id), -- Clé étrangère vers la table des produits

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Auto-generated timestamp of card creation
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP  -- Auto-generated timestamp of last update

);

-- Indexes for optimized search (optional)
CREATE INDEX IF NOT EXISTS idx_business_card_name ON business_card (first_name, last_name);
CREATE INDEX IF NOT EXISTS idx_business_card_email ON business_card (email);
CREATE INDEX IF NOT EXISTS idx_business_card_phone ON business_card (phone);

-- Example insert (this shows how data can be inserted based on the form fields)
-- INSERT INTO business_card (first_name, last_name, companie, competence, domaine, email, phone, address, slogan, details) VALUES ('John', 'Doe', 'Tech Solutions', 'Software Engineer', 'Technology', 'john.doe@example.com', '123-456-7890', '123 Elm Street', 'Innovating the Future', 'Lorem ipsum...');
