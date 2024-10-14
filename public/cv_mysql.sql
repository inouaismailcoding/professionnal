-- Table pour les informations personnelles
CREATE TABLE IF NOT EXISTS personal_information (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    first_name VARCHAR(255) NOT NULL, -- Prénom du candidat
    last_name VARCHAR(255) NOT NULL, -- Nom de famille du candidat
    email VARCHAR(255) NOT NULL UNIQUE, -- Adresse email unique
    phone VARCHAR(20) NOT NULL, -- Numéro de téléphone
    address TEXT, -- Adresse complète
    linkedin_url VARCHAR(255), -- URL du profil LinkedIn
    github_url VARCHAR(255), -- URL du profil GitHub
    portfolio_url VARCHAR(255), -- URL du portfolio
    resume TEXT, -- Résumé ou biographie professionnelle
    photo BLOB, -- Photo de profil (stockée sous forme de BLOB)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Date de mise à jour
);

-- Table pour l'historique de l'éducation
CREATE TABLE IF NOT EXISTS education (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    institution_name VARCHAR(255) NOT NULL, -- Nom de l'institution académique
    degree VARCHAR(255) NOT NULL, -- Diplôme obtenu
    field_of_study VARCHAR(255), -- Domaine d'étude
    start_date DATE NOT NULL, -- Date de début des études
    end_date DATE, -- Date de fin des études (NULL si les études sont en cours)
    honors VARCHAR(255), -- Mention ou distinction (facultatif)
    description TEXT, -- Description supplémentaire (facultatif)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les expériences professionnelles
CREATE TABLE IF NOT EXISTS work_experience (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    company_name VARCHAR(255) NOT NULL, -- Nom de l'entreprise
    job_title VARCHAR(255) NOT NULL, -- Intitulé du poste
    start_date DATE NOT NULL, -- Date de début du poste
    end_date DATE, -- Date de fin du poste (NULL pour le poste actuel)
    description TEXT, -- Description des responsabilités et réalisations
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les compétences
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    skill_name VARCHAR(255) NOT NULL, -- Nom de la compétence
    skill_level VARCHAR(50) NOT NULL, -- Niveau de compétence (ex : Débutant, Intermédiaire, Avancé)
    description TEXT, -- Description ou détails supplémentaires (facultatif)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les certifications et récompenses
CREATE TABLE IF NOT EXISTS certifications_awards (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    title VARCHAR(255) NOT NULL, -- Titre de la certification ou de la récompense
    organization VARCHAR(255) NOT NULL, -- Organisation ayant délivré la certification ou récompense
    date_awarded DATE NOT NULL, -- Date d'obtention
    description TEXT, -- Détails supplémentaires (facultatif)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les langues
CREATE TABLE IF NOT EXISTS languages (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    language VARCHAR(255) NOT NULL, -- Nom de la langue
    proficiency_level VARCHAR(50) NOT NULL, -- Niveau de maîtrise (ex : Basique, Conversationnel, Courant, Natif)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les projets ou portefeuilles
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    project_name VARCHAR(255) NOT NULL, -- Nom du projet
    role VARCHAR(255) NOT NULL, -- Rôle du candidat dans le projet
    description TEXT NOT NULL, -- Description détaillée du projet
    technologies_used TEXT, -- Technologies ou outils utilisés (facultatif)
    project_url VARCHAR(255), -- URL du projet ou de la démo (facultatif)
    start_date DATE, -- Date de début du projet
    end_date DATE, -- Date de fin du projet
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les loisirs ou centres d'intérêt
CREATE TABLE IF NOT EXISTS hobbies_interests (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    hobby_name VARCHAR(255) NOT NULL, -- Nom du loisir ou centre d'intérêt
    description TEXT, -- Description supplémentaire (facultatif)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les références
CREATE TABLE IF NOT EXISTS references (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    reference_name VARCHAR(255) NOT NULL, -- Nom de la référence
    contact_information VARCHAR(255) NOT NULL, -- Coordonnées de la référence (email ou téléphone)
    relationship VARCHAR(255) NOT NULL, -- Relation entre la référence et le candidat
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Table pour les adhésions professionnelles (facultatif)
CREATE TABLE IF NOT EXISTS professional_memberships (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque enregistrement
    personal_information_id INT NOT NULL, -- Clé étrangère référencée dans personal_information
    organization VARCHAR(255) NOT NULL, -- Organisation à laquelle le candidat est adhérent
    membership_level VARCHAR(255), -- Niveau d'adhésion (facultatif)
    start_date DATE, -- Date de début de l'adhésion
    end_date DATE, -- Date de fin de l'adhésion (NULL si en cours)
    description TEXT, -- Description ou détails supplémentaires (facultatif)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Date de mise à jour
    FOREIGN KEY (personal_information_id) REFERENCES personal_information(id) ON DELETE CASCADE -- Contrainte d'intégrité référentielle
);

-- Création d'index pour accélérer les recherches
CREATE INDEX IF NOT EXISTS idx_personal_information_name ON personal_information (first_name, last_name);
CREATE INDEX IF NOT EXISTS idx_personal_information_email ON personal_information (email);
CREATE INDEX IF NOT EXISTS idx_work_experience_dates ON work_experience (start_date, end_date);
CREATE INDEX IF NOT EXISTS idx_education_dates ON education (start_date, end_date);
