CREATE TABLE users (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  username  VARCHAR(20) NOT NULL,
  email     VARCHAR(255) NOT NULL,
  password  VARCHAR(255) NOT NULL,
  created   DATETIME
);

INSERT INTO users(username, email, password, created)
  VALUES (
    'root', 'mri@ksu.edu', 'pass', NOW()
);
