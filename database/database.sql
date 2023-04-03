
CREATE TABLE user(
    id INTEGER PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    role ENUM('client', 'agent', 'admin') NOT NULL
);

CREATE TABLE department(
    id INTEGER PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE tickets(
    id INTEGER PRIMARY KEY,
    department_id INTEGER,
    client_id INTEGER,
    agent_id INTEGER,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'assigned', 'closed') NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (client_id) REFERENCES user(id),
    FOREIGN KEY (agent_id) REFERENCES user(id)
);

CREATE TABLE message(
    id INTEGER PRIMARY KEY,
    msg TEXT NOT NULL,
    ticket_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

CREATE TABLE faq(
  id INTEGER PRIMARY KEY,
  question TEXT NOT NULL,
  answer TEXT NOT NULL
);

CREATE TABLE hashtags (
  id INTEGER PRIMARY KEY,
  hashtag VARCHAR(50) NOT NULL
);

CREATE TABLE ticket_hashtags (
  ticket_id INT NOT NULL,
  hashtag_id INT NOT NULL,
  PRIMARY KEY (ticket_id, hashtag_id),
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
  FOREIGN KEY (hashtag_id) REFERENCES hashtags(id) ON DELETE CASCADE
);


INSERT INTO faq VALUES (1,'addd','afawffa');
