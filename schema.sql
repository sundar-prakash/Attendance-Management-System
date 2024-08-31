-- Create the 'users' table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- Create the 'attendance' table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date DATE NOT NULL,
    time TIME NOT NULL,
    type ENUM('check_in', 'check_out') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE `password_resets` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expiry` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin user
INSERT INTO users (username, password, name, email, role) 
VALUES ('sundar', '$2y$10$NFIL5Fo3Rb265kCHO8JkL.crNq./XI0aL7umiWZGuFQco3QodVAiu', 'Sundar', 'psundarprakash603@gmail.com', 'admin');
INSERT INTO users (username, password, name, email, role) 
VALUES ('maran', '$2y$10$kMHNMANBKKxEjdAQoFmWeOahrEoVdmQwH5F8GFIDvEW.tO01IQy2G', 'Sukumaran', 'sugumaran713@gmail.com', 'admin');


