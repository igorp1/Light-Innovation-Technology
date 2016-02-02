-- CREATE units TABLE --
CREATE TABLE units(
  id int NOT NULL AUTO_INCREMENT,
  addr varchar(40),
  status int ,
  position_x int ,
  position_y int ,
  owner int,
  PRIMARY KEY (id)
)

-- CREATE users TABLE --
CREATE TABLE users(
  id int NOT NULL AUTO_INCREMENT,
  email varchar(50),
  password varchar(32),
  username varchar(30),
  PRIMARY KEY (id)
)

-- CREATE status TABLE --
CREATE TABLE status(
  id int NOT NULL AUTO_INCREMENT,
  color varchar(7),
  description varchar(255),
  name varchar(20),
  PRIMARY KEY (id)
)

-- ADD STATUS TABLES VALUES--
INSERT INTO status (`name`, `description`, `color` )
            VALUES ("OK","Your device is up and running.","#00FF55");
INSERT INTO status (`name`, `description`, `color` )
            VALUES ('OFF',  "Just flip the switch and we are good to go.",  "#1a8cff");
INSERT INTO status (`name`, `description`, `color` )
            VALUES ('UNREACHABLE',  "We are having a connectivity issue.",  "#ff3333");
