/*
Clean tables.

DROP TABLE IF EXISTS statuses CASCADE;
DROP TABLE IF EXISTS users CASCADE;
*/

CREATE TABLE IF NOT EXISTS statuses (
  id          int           NOT NULL AUTO_INCREMENT,
  username    VARCHAR(30)   NOT NULL,
  message     VARCHAR(140)  NOT NULL,
  date        DATETIME,
  clientused  VARCHAR(30),
  PRIMARY KEY (id)
) ENGINE = MYISAM CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS users (
  id        int         NOT NULL AUTO_INCREMENT,
  username  VARCHAR(30) NOT NULL,
  password  VARCHAR(70) NOT NULL,
  PRIMARY KEY (id)
) ENGINE = MYISAM CHARACTER SET = utf8;
