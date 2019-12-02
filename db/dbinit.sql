DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS steps;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS recipe_tag_junction;
DROP TABLE IF EXISTS tags;

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
  vehicle_type  ENUM('post', 'comment'),
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
  slug        VARCHAR(50),
  title       VARCHAR(50) UNIQUE NOT NULL,
  author      VARCHAR(20),
  description VARCHAR(8192),
  CONSTRAINT fk_author
    FOREIGN KEY (author)
    REFERENCES users(username)
);

CREATE TABLE comments (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  parent_id         INT,
  parent_commenter  VARCHAR(20),
  commenter         VARCHAR(20),
  post_id           INT,
  body              VARCHAR(1024),
  CONSTRAINT fk_parent
    FOREIGN KEY (parent_id)
    REFERENCES comments(id),
  CONSTRAINT fk_parent_c
    FOREIGN KEY (parent_commenter)
    REFERENCES users(username),
  CONSTRAINT fk_commenter
    FOREIGN KEY (commenter)
    REFERENCES users(username),
  CONSTRAINT fk_post
    FOREIGN KEY (post_id)
    REFERENCES posts(id)
);

CREATE TABLE tags (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  name      VARCHAR(20) NOT NULL,
  genre     ENUM('Meal', 'Cuisine', 'Ethics', 'Allergies', 'Other') NOT NULL,
  hex_color VARCHAR(6),
  icon_loc  VARCHAR(255)
);

CREATE TABLE recipe_tag_junction (
  tag_id    INT,
  recipe_id INT,

  CONSTRAINT fk_tag
    FOREIGN KEY (tag_id)
    REFERENCES tags(id),
  CONSTRAINT fk_recipe
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
);

INSERT INTO tags
  (name, genre)
VALUES
  ('Breakfast', 'Meal'),
  ('Lunch', 'Meal'),
  ('Dinner', 'Meal'),
  ('Dessert', 'Meal'),
  ('Snack', 'Meal'),
  ('American', 'Cuisine'),
  ('Japanese', 'Cuisine'),
  ('Italian', 'Cuisine'),
  ('Mediterranean', 'Cuisine'),
  ('African', 'Cuisine'),
  ('Brazilian', 'Cuisine'),
  ('Vegetarian', 'Ethics'),
  ('Vegan', 'Ethics'),
  ('Gluten-Free', 'Allergies'),
  ('Nut-Free', 'Allergies'),
  ('Low Price', 'Other')
;
