-- CREATE units TABLE --
CREATE TABLE units(
  id int NOT NULL AUTO_INCREMENT,
  addr varchar(40),
  status int,
  position_x int,
  position_y int,
  width int,
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

-- CREATE setups TABLE --
CREATE TABLE setups(
  id int NOT NULL AUTO_INCREMENT,
  name varchar(30),
  owner int,
  PRIMARY KEY(id)
);

-- CREATE setup_positions TABLE --
CREATE TABLE setup_positions(
  pos_id int NOT NULL AUTO_INCREMENT,
  unit_id int,
  setup_id int,
  pos_x int,
  pos_y int,
  width int,
  PRIMARY KEY(pos_id)
);

-- ADD A COUPLE SETUPS --
INSERT INTO setups(`name`, `owner`)
            VALUES("Movie", 29);
INSERT INTO setups(`name`, `owner`)
            VALUES("Dinner", 29);
INSERT INTO setups(`name`, `owner`)
            VALUES("TV", 29);

-- ADD A FEW UNITS TO THE SETUPS --
INSERT INTO setup_positions(`unit_id`,`pos_x`,`pos_y`,`width`,`setup_id`)
                     VALUES(2,-12, 11,  2, 1);
INSERT INTO setup_positions(`unit_id`,`pos_x`,`pos_y`,`width`,`setup_id`)
                     VALUES(2,-82, 11,  2, 2);
INSERT INTO setup_positions(`unit_id`,`pos_x`,`pos_y`,`width`,`setup_id`)
                     VALUES(2,  0,  0,  1, 3);
INSERT INTO setup_positions(`unit_id`,`pos_x`,`pos_y`,`width`,`setup_id`)
                     VALUES(3,-63,  22, 5, 1);
INSERT INTO setup_positions(`unit_id`,`pos_x`,`pos_y`,`width`,`setup_id`)
                     VALUES(3,  0,  43, 1, 2);
INSERT INTO setup_positions(`unit_id`,`pos_x`,`pos_y`,`width`,`setup_id`)
                     VALUES(3,-89, -98, 3, 3);
