CREATE DATABASE IF NOT EXISTS `portfolio`;


CREATE TABLE IF NOT EXISTS `portfolio`.`users` 
(`id` INT NOT NULL AUTO_INCREMENT , 
`username` VARCHAR(200) NOT NULL , 
`email` VARCHAR(200) NOT NULL ,
`password` TEXT NOT NULL , 
`phone` VARCHAR(200) NOT NULL , 
`role` ENUM('staff','manager','admin','') NOT NULL DEFAULT 'staff' ,
`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
`updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), UNIQUE (`username`), UNIQUE (`email`)) 
ENGINE = InnoDB;

CREATE TABLE user_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_time DATETIME NOT NULL,
    logout_time DATETIME DEFAULT NULL,
    session_duration TIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)ENGINE = InnoDB;


CREATE TABLE user_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action_type ENUM('create', 'update', 'delete', 'view') NOT NULL,
    transaction_details TEXT NOT NULL,
    transaction_time DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)ENGINE = InnoDB;

