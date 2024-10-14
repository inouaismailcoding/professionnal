-- Table pour les informations personnelles
CREATE TABLE IF NOT EXISTS informations_personnelles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prenom TEXT NOT NULL,
    nom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    telephone TEXT NOT NULL,
    adresse TEXT,
    linkedin_url TEXT,
    github_url TEXT,
    portfolio_url TEXT,
    resume TEXT,  -- Brève biographie ou résumé professionnel
    photo BLOB,  -- Facultatif : Stocker une photo de profil sous forme de BLOB
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table pour l'historique de l'éducation
CREATE TABLE IF NOT EXISTS education (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL, -- Clé étrangère référencée dans informations_personnelles
    nom_institution TEXT NOT NULL,
    diplome TEXT NOT NULL,
    domaine_etude TEXT,
    date_debut DATE NOT NULL,
    date_fin DATE,  -- Null si les études sont en cours
    mention TEXT,  -- Facultatif : Mention, note ou autre qualification
    description TEXT,  -- Facultatif : Détails ou points forts supplémentaires
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les expériences professionnelles
CREATE TABLE IF NOT EXISTS experience_professionnelle (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    nom_entreprise TEXT NOT NULL,
    poste TEXT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE,  -- Null pour le poste actuel
    description TEXT,  -- Description détaillée des responsabilités et réalisations
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les compétences
CREATE TABLE IF NOT EXISTS competences (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    nom_competence TEXT NOT NULL,
    niveau_competence TEXT NOT NULL,  -- Par exemple : Débutant, Intermédiaire, Avancé, Expert
    description TEXT,  -- Facultatif : Explication ou expérience liée à la compétence
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les certifications et récompenses
CREATE TABLE IF NOT EXISTS certifications_recompenses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    titre TEXT NOT NULL,
    organisation TEXT NOT NULL,  -- Organisation ayant délivré la certification ou la récompense
    date_obtention DATE NOT NULL,
    description TEXT,  -- Facultatif : Détails supplémentaires
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les langues
CREATE TABLE IF NOT EXISTS langues (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    langue TEXT NOT NULL,
    niveau_maitrise TEXT NOT NULL,  -- Par exemple : Basique, Conversationnel, Courant, Natif
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les projets ou portefeuilles
CREATE TABLE IF NOT EXISTS projets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    nom_projet TEXT NOT NULL,
    role TEXT NOT NULL,  -- Le rôle du candidat dans le projet
    description TEXT NOT NULL,  -- Description détaillée du projet
    technologies_utilisees TEXT,  -- Liste des technologies ou outils utilisés
    url_projet TEXT,  -- Facultatif : URL du projet ou de la démo
    date_debut DATE,
    date_fin DATE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les loisirs ou centres d'intérêt
CREATE TABLE IF NOT EXISTS loisirs_interets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    nom_loisir TEXT NOT NULL,
    description TEXT,  -- Facultatif : Informations supplémentaires sur le loisir
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les références
CREATE TABLE IF NOT EXISTS references (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    nom_reference TEXT NOT NULL,
    contact_reference TEXT NOT NULL,  -- Email ou téléphone
    relation TEXT NOT NULL,  -- Relation entre la référence et le candidat
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Table pour les adhésions professionnelles (facultatif)
CREATE TABLE IF NOT EXISTS adhesions_professionnelles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    informations_personnelles_id INTEGER NOT NULL,  -- Clé étrangère référencée dans informations_personnelles
    organisation TEXT NOT NULL,
    niveau_adhesion TEXT,  -- Facultatif : Niveau d'adhésion
    date_debut DATE,
    date_fin DATE,  -- Null pour les adhésions en cours
    description TEXT,  -- Facultatif : Détails supplémentaires sur l'adhésion
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (informations_personnelles_id) REFERENCES informations_personnelles(id) ON DELETE CASCADE
);

-- Création d'index pour accélérer les recherches sur des champs fréquemment utilisés
CREATE INDEX IF NOT EXISTS idx_informations_personnelles_nom ON informations_personnelles (prenom, nom);
CREATE INDEX IF NOT EXISTS idx_informations_personnelles_email ON informations_personnelles (email);
CREATE INDEX IF NOT EXISTS idx_experience_professionnelle_dates ON experience_professionnelle (date_debut, date_fin);
CREATE INDEX IF NOT EXISTS idx_education_dates ON education (date_debut, date_fin);
