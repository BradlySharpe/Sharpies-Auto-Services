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

--  Create Saftey Check table
CREATE TABLE safteyCheck (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

--  Create Service table
CREATE TABLE service (
  id INT AUTO_INCREMENT PRIMARY KEY,
  car INT NOT NULL,
  odo INT(6) NOT NULL,
  safteyCheck INT NOT NULL,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

--    Add Saftey Check Foreign Keys
ALTER TABLE `service`
  ADD INDEX `car` (`car`),
  ADD INDEX `safteyCheck` (`safteyCheck`),
  ADD CONSTRAINT fk_service_carId
    FOREIGN KEY (car)
      REFERENCES car(id),
  ADD CONSTRAINT fk_service_safteyCheckId
    FOREIGN KEY (safteyCheck)
      REFERENCES safteyCheck(id);

--  Create Invoice table
CREATE TABLE invoice (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service INT NOT NULL,
  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

--    Add Service Foreign Key
ALTER TABLE `invoice`
  ADD INDEX `service` (`service`),
  ADD CONSTRAINT fk_invoice_serviceId
    FOREIGN KEY (service)
      REFERENCES service(id);

--  Create Item table
CREATE TABLE item (
  id INT AUTO_INCREMENT PRIMARY KEY,
  description INT NOT NULL,
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
  item INT NOT NULL,
  comment TEXT NULL,
  cost FLOAT NOT NULL,
  quantity FLOAT NOT NULL
);

--    Add Detail Foreign Keys
ALTER TABLE `detail`
  ADD INDEX `invoice` (`invoice`),
  ADD INDEX `item` (`item`),
  ADD CONSTRAINT fk_detail_invoiceId
    FOREIGN KEY (invoice)
      REFERENCES invoice(id),
  ADD CONSTRAINT fk_detail_itemId
    FOREIGN KEY (item)
      REFERENCES item(id);

--  Create Payment table
CREATE TABLE payment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice INT NOT NULL,
  amount FLOAT NOT NULL,
  comment TEXT NULL,
  date DATE NOT NULL
);


--  Populate with example customer
INSERT INTO customer
  (`firstname`, `lastname`, `address`, `city`, `state`, `postcode`)
VALUES
  ('Bradly', 'Sharpe', '123 Fake Address', 'Warrnambool', 'Victoria', 3280),
  ('Warren', 'Sharpe', '456 Fake Address', 'Warrnambool', 'Victoria', 3280);
