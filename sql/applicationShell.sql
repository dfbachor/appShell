drop table if exists users;

CREATE TABLE users (
  ID int(11) NOT NULL AUTO_INCREMENT,
  username varchar(64) NOT NULL,
  firstname varchar(64) NOT NULL,
  lastname varchar(64) NOT NULL,
  email varchar(255) NOT NULL,
  rawPassword varchar(256) NOT NULL,
  hashPassword varchar(256) NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE KEY username (username)
);




drop table if exists rememberMe;

CREATE TABLE rememberMe (
  ID int(11) NOT NULL AUTO_INCREMENT,
  token varchar(64) NOT NULL,
  userName varchar(64) NOT NULL,  
  tokenDate date NOT NULL,  
  PRIMARY KEY (ID),
  UNIQUE KEY token (token)
);


drop table if exists resetToken;

CREATE TABLE resetToken (
  ID int(11) NOT NULL AUTO_INCREMENT,
  token varchar(64) NOT NULL,
  userName varchar(64) NOT NULL,  
  tokenDate date NOT NULL,  
  PRIMARY KEY (ID),
  UNIQUE KEY token (token)
);