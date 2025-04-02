-- plugins/generic/customContributorFields/sql/install.sql

-- Tabela para definir os campos personalizados disponíveis
CREATE TABLE custom_contributor_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journal_id INT NOT NULL,
    field_name VARCHAR(255) NOT NULL,
    field_type VARCHAR(50) NOT NULL,
    is_required TINYINT(1) DEFAULT 0,
    is_public TINYINT(1) DEFAULT 0,
    show_on_form TINYINT(1) DEFAULT 1,
    show_on_profile TINYINT(1) DEFAULT 1,
    settings TEXT,
    FOREIGN KEY (journal_id) REFERENCES journals(journal_id) ON DELETE CASCADE
);

-- Tabela para armazenar os valores inseridos nos campos pelos autores
CREATE TABLE custom_contributor_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    field_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    author_id INT DEFAULT NULL,
    value TEXT,
    FOREIGN KEY (field_id) REFERENCES custom_contributor_fields(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES authors(author_id) ON DELETE CASCADE
);
