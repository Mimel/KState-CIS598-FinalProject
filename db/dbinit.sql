DROP TABLE IF EXISTS users;

CREATE TABLE users (
  username  VARCHAR(20) PRIMARY KEY,
  email     VARCHAR(40) UNIQUE NOT NULL,
  password  VARCHAR(256) NOT NULL,
  rank      ENUM('user', 'mod', 'admin') NOT NULL,
  created   DATETIME
);

CREATE TABLE recipes (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  parent  INT,
  steps   VARCHAR(8192),
  CONSTRAINT fk_parent
    FOREIGN KEY (parent)
    REFERENCES recipes(id)
);

CREATE TABLE ingredients (
  recipe_id INT,
  amount    VARCHAR(20),
  name      VARCHAR(50),
  CONSTRAINT fk_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
);

CREATE TABLE posts (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(50) UNIQUE NOT NULL,
  slug        VARCHAR(50) UNIQUE NOT NULL,
  author      VARCHAR(20),
  recipe_id   INT,
  description VARCHAR(8192),
  CONSTRAINT fk_author
    FOREIGN KEY (author)
    REFERENCES users(username),
  CONSTRAINT fk_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
);
