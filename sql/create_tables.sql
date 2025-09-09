-- Base de données pour OmnesScreen
CREATE DATABASE IF NOT EXISTS omnes_screen;
USE omnes_screen;

-- Table des utilisateurs (associations et administrateurs)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('association', 'admin') NOT NULL,
    association_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Table des événements
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    campus ENUM('citadelle', 'citroen') NOT NULL,
    association_name VARCHAR(100) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertion d'un administrateur par défaut
INSERT INTO users (username, password, email, role, association_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@omnes.fr', 'admin', 'Administration');

-- Insertion d'une association d'exemple
INSERT INTO users (username, password, email, role, association_name) VALUES 
('bde_citadelle', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bde.citadelle@omnes.fr', 'association', 'BDE Citadelle');

-- Insertion d'événements d'exemple
INSERT INTO events (title, description, event_date, event_time, location, campus, association_name, status, created_by) VALUES 
('Soirée étudiante', 'Grande soirée organisée par le BDE', '2025-09-15', '20:00:00', 'Amphithéâtre A', 'citadelle', 'BDE Citadelle', 'approved', 2),
('Conférence Tech', 'Conférence sur les nouvelles technologies', '2025-09-20', '14:00:00', 'Salle de conférence', 'citroen', 'BDE Citroën', 'approved', 2),
('Tournoi Gaming', 'Tournoi de jeux vidéo inter-campus', '2025-09-25', '18:00:00', 'Salle informatique', 'citadelle', 'Gaming Club', 'pending', 2);
