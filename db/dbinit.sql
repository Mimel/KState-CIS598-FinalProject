DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS steps;
DROP TABLE IF EXISTS posts;

CREATE TABLE users (
  username  VARCHAR(20) PRIMARY KEY,
  email     VARCHAR(40) UNIQUE NOT NULL,
  password  VARCHAR(255) NOT NULL,
  rank      ENUM('user', 'mod', 'admin') NOT NULL,
  created   DATETIME
);

CREATE TABLE recipes (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id    INT,
  parent        INT,
  CONSTRAINT fk_parent
    FOREIGN KEY (parent)
    REFERENCES recipes(id),
  CONSTRAINT fk_vehicle
    FOREIGN KEY (vehicle_id)
    REFERENCES posts(id)
);

CREATE TABLE ingredients (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT,
  amount    VARCHAR(20),
  name      VARCHAR(50),
  CONSTRAINT fk_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
);

CREATE TABLE steps (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT,
  step      VARCHAR(255),
  CONSTRAINT fk_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
);

CREATE TABLE posts (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(50) UNIQUE NOT NULL,
  slug        VARCHAR(50) UNIQUE NOT NULL,
  author      VARCHAR(20),
  description VARCHAR(8192),
  CONSTRAINT fk_author
    FOREIGN KEY (author)
    REFERENCES users(username)
);
