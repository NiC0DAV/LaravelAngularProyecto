CREATE DATABASE IF NOT EXISTS api_rest_laravel;
USE api_rest_laravel;

CREATE TABLE users(
    id INT(255) AUTO_INCREMENT NOT NULL,
    name VARCHAR (50) NOT NULL,
    surname VARCHAR (100),
    role VARCHAR (20),
    email VARCHAR (255) NOT NULL,
    password VARCHAR (255) NOT NULL,
    description TEXT,
    image VARCHAR (255),
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    remember_token VARCHAR (255),
    CONSTRAINT PK_users_table PRIMARY KEY (id)
)ENGINE=InnoDb;

INSERT INTO users (name,surname,role,email,password,) 
    VALUES('Nicolas','Estevez','ROLE_ADMIN','esteveznicolas0@gmail.com','admin');

CREATE TABLE categories(
    id INT(255) NOT NULL AUTO_INCREMENT,
    name VARCHAR (100),
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT PK_categories_table PRIMARY KEY (id)
)ENGINE=InnoDb;



CREATE TABLE posts(
    id INT(255) NOT NULL AUTO_INCREMENT,
    user_id INT(255) NOT NULL,
    category_id INT(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,    
    CONSTRAINT PK_posts_table PRIMARY KEY (id),
    CONSTRAINT FK_users_table FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT FK_categories_table FOREIGN KEY (category_id) REFERENCES categories(id)
)ENGINE=InnoDb;


