
DROP TABLE IF EXISTS user;
CREATE TABLE user(
    id INTEGER PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(10) CHECK(role = 'client' or role = 'agent' or role = 'admin') NOT NULL,
    department_id INTEGER,
    FOREIGN KEY (department_id) REFERENCES department(id)
);

DROP TABLE IF EXISTS department;
CREATE TABLE department(
    id INTEGER PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

DROP TABLE IF EXISTS tickets;
CREATE TABLE tickets(
    id INTEGER PRIMARY KEY,
    department_id INTEGER,
    client_id INTEGER,
    agent_id INTEGER,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(10) CHECK(status = 'open' or status = 'assigned' or status = 'closed') NOT NULL,
    priority VARCHAR(10) CHECK(priority = 'low' or priority = 'medium' or priority ='high') NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (client_id) REFERENCES user(id),
    FOREIGN KEY (agent_id) REFERENCES user(id)
);

DROP TABLE IF EXISTS message;
CREATE TABLE message(
    id INTEGER PRIMARY KEY,
    msg TEXT NOT NULL,
    ticket_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);

DROP TABLE IF EXISTS faq;
CREATE TABLE faq(
  id INTEGER PRIMARY KEY,
  question TEXT NOT NULL,
  answer TEXT NOT NULL
);

DROP TABLE IF EXISTS hashtags;
CREATE TABLE hashtags (
  id INTEGER PRIMARY KEY,
  hashtag VARCHAR(50) NOT NULL
);

DROP TABLE IF EXISTS ticket_hashtags;
CREATE TABLE ticket_hashtags (
  ticket_id INT NOT NULL,
  hashtag_id INT NOT NULL,
  PRIMARY KEY (ticket_id, hashtag_id),
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
  FOREIGN KEY (hashtag_id) REFERENCES hashtags(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS changesToTicket;
CREATE TABLE changesToTicket (
  id INTEGER PRIMARY KEY,
  user_id INTEGER,
  ticket_id INTEGER,
  change TEXT NOT NULL,
  date DATETIME,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
);

