DROP DATABASE IF EXISTS autoservice;

CREATE DATABASE autoservice;
USE autoservice;

--  Create Customer table
CREATE TABLE customer (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  address VARCHAR(100) NOT NULL,
  city VARCHAR(50) NOT NULL,
  state VARCHAR(20) NOT NULL,
  postcode INT(4) NOT NULL,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

--  Create Car table
CREATE TABLE car (
  id INT AUTO_INCREMENT PRIMARY KEY,
  owner INT NOT NULL,
  make VARCHAR(100) NOT NULL,
  model VARCHAR(100) NOT NULL,
  registration VARCHAR(6) NOT NULL,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

--    Add Owner Foreign Key
ALTER TABLE `car`
  ADD UNIQUE(`registration`),
  ADD INDEX `owner` (`owner`),
  ADD CONSTRAINT fk_car_ownerId
    FOREIGN KEY (owner)
      REFERENCES customer(id);

--  Create Safety Check table
CREATE TABLE safetyCheck (
  id INT AUTO_INCREMENT PRIMARY KEY,
  completed BOOLEAN NULL DEFAULT FALSE,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
--    Set ID to 1000
ALTER TABLE safetyCheck AUTO_INCREMENT=1000;

--  Create Service table
CREATE TABLE service (
  id INT AUTO_INCREMENT PRIMARY KEY,
  owner INT NOT NULL,
  car INT NOT NULL,
  odo INT(6) NOT NULL,
  safetyCheck INT NOT NULL,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
--    Set ID to 1000
ALTER TABLE service AUTO_INCREMENT=1000;

--    Add Safety Check Foreign Keys
ALTER TABLE `service`
  ADD INDEX `owner` (`owner`),
  ADD INDEX `car` (`car`),
  ADD INDEX `safetyCheck` (`safetyCheck`),
  ADD CONSTRAINT fk_service_ownerId
    FOREIGN KEY (owner)
      REFERENCES Customer(id),
  ADD CONSTRAINT fk_service_carId
    FOREIGN KEY (car)
      REFERENCES car(id),
  ADD CONSTRAINT fk_service_safetyCheckId
    FOREIGN KEY (safetyCheck)
      REFERENCES safetyCheck(id);

--  Create Invoice table
CREATE TABLE invoice (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service INT NOT NULL,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
--    Set ID to 1000
ALTER TABLE invoice AUTO_INCREMENT=1000;

--    Add Service Foreign Key
ALTER TABLE `invoice`
  ADD INDEX `service` (`service`),
  ADD CONSTRAINT fk_invoice_serviceId
    FOREIGN KEY (service)
      REFERENCES service(id);

--  Create Item table
CREATE TABLE item (
  id INT AUTO_INCREMENT PRIMARY KEY,
  description TEXT NOT NULL,
  defaultCost FLOAT NULL,
  defaultQuantity FLOAT NULL,
  comment BOOLEAN NULL DEFAULT TRUE,
  active BOOLEAN NULL DEFAULT TRUE,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

--  Create Detail table
CREATE TABLE detail (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice INT NOT NULL,
  description TEXT NOT NULL,
  comment TEXT NULL,
  cost FLOAT NOT NULL,
  quantity FLOAT NOT NULL
);

--    Add Detail Foreign Keys
ALTER TABLE `detail`
  ADD INDEX `invoice` (`invoice`),
  ADD CONSTRAINT fk_detail_invoiceId
    FOREIGN KEY (invoice)
      REFERENCES invoice(id);

--  Create Payment table
CREATE TABLE payment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice INT NOT NULL,
  amount FLOAT NOT NULL,
  comment TEXT NULL,
  date DATE NOT NULL
);
