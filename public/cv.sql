-- Table for personal information
CREATE TABLE IF NOT EXISTS personal_info (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    phone TEXT NOT NULL,
    address TEXT,
    linkedin_url TEXT,
    github_url TEXT,
    portfolio_url TEXT,
    summary TEXT,  -- Short bio or professional summary
    photo BLOB,  -- Optional: Store a profile picture as a BLOB
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table for education history
CREATE TABLE IF NOT EXISTS education (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL, -- Foreign key referencing personal_info
    institution_name TEXT NOT NULL,
    degree TEXT NOT NULL,
    field_of_study TEXT,
    start_date DATE NOT NULL,
    end_date DATE,  -- Nullable for ongoing education
    grade TEXT,  -- Optional: Grade, GPA, or other qualification
    description TEXT,  -- Optional: Additional details or highlights
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for work experience
CREATE TABLE IF NOT EXISTS work_experience (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    company_name TEXT NOT NULL,
    job_title TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,  -- Nullable for current job
    description TEXT,  -- Detailed description of responsibilities and achievements
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for skills
CREATE TABLE IF NOT EXISTS skills (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    skill_name TEXT NOT NULL,
    skill_level TEXT NOT NULL,  -- Could be: Beginner, Intermediate, Advanced, Expert
    description TEXT,  -- Optional: Additional explanation or related experience
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for certifications and awards
CREATE TABLE IF NOT EXISTS certifications_awards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    title TEXT NOT NULL,
    organization TEXT NOT NULL,  -- Organization that issued the certificate/award
    date_issued DATE NOT NULL,
    description TEXT,  -- Optional: Additional details
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for languages
CREATE TABLE IF NOT EXISTS languages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    language TEXT NOT NULL,
    proficiency_level TEXT NOT NULL,  -- Could be: Basic, Conversational, Fluent, Native
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for projects or portfolios
CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    project_name TEXT NOT NULL,
    role TEXT NOT NULL,  -- The role of the candidate in the project
    description TEXT NOT NULL,  -- Detailed description of the project
    technologies_used TEXT,  -- List of technologies or tools used in the project
    project_url TEXT,  -- Optional: URL to the project or demo
    start_date DATE,
    end_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for hobbies or interests
CREATE TABLE IF NOT EXISTS hobbies_interests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    hobby_name TEXT NOT NULL,
    description TEXT,  -- Optional: Additional information about the hobby
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for references
CREATE TABLE IF NOT EXISTS references (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    reference_name TEXT NOT NULL,
    reference_contact TEXT NOT NULL,  -- Could be email or phone
    relationship TEXT NOT NULL,  -- How the reference is related to the candidate
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Table for professional memberships (optional)
CREATE TABLE IF NOT EXISTS professional_memberships (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    personal_info_id INTEGER NOT NULL,  -- Foreign key referencing personal_info
    organization TEXT NOT NULL,
    membership_level TEXT,  -- Optional: Level of membership
    start_date DATE,
    end_date DATE,  -- Nullable for ongoing memberships
    description TEXT,  -- Optional: Additional details about the membership
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (personal_info_id) REFERENCES personal_info(id) ON DELETE CASCADE
);

-- Create indexes for faster queries on commonly searched fields
CREATE INDEX IF NOT EXISTS idx_personal_info_name ON personal_info (first_name, last_name);
CREATE INDEX IF NOT EXISTS idx_personal_info_email ON personal_info (email);
CREATE INDEX IF NOT EXISTS idx_work_experience_dates ON work_experience (start_date, end_date);
CREATE INDEX IF NOT EXISTS idx_education_dates ON education (start_date, end_date);
