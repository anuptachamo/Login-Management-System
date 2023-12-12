
-- COPY & PASTE the required script to create table inside your database automatically.
-- Suppose your databse name is "signup". Select the database > go to "SQL" and paste this sql query and click "GO" to run it.

CREATE TABLE registration(
   id INT(255) NOT NULL AUTO_INCREMENT,
   username VARCHAR(255) NOT NULL,
   email VARCHAR(255) NOT NULL,
   password VARCHAR(255) NOT NULL,
   cpassword VARCHAR(255) NOT NULL,
   code TEXT,
   PRIMARY KEY ( id )
);
