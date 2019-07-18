CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birthdate` datetime NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` boolean NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `item` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `owner` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `product_image` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `is_active` boolean NOT NULL,
  `sold` boolean NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO item (owner, title, description, price, product_image, category, is_active, sold, created_at, updated_at) VALUES (-1, 'EarPods Apple Originales', 'Accesorios de Apple Originales y Auténticos, vienen con su Certificado de Autenticidad y garantía de Apple. TOTALMENTE PRECINTADOS Y NUEVOS', 19, 1, 'Computers and electronic', true, false, '2019-05-15 21:05:00', '2019-05-15 21:05:00');
INSERT INTO item (owner, title, description, price, product_image, category, is_active, sold, created_at, updated_at) VALUES (-1, 'BMW Serie 3 2017', 'Se vende BMW 318d Touring en perfecto estado, matriculado en Mayo 2017, se vende con mantenimiento incluido durante 3 años', 24000, 2, 'Cars', true, false, '2019-05-15 21:11:00', '2019-05-15 21:11:00');
INSERT INTO item (owner, title, description, price, product_image, category, is_active, sold, created_at, updated_at) VALUES (-1, 'Guantes de Boxeo para Principiantes', 'Guantes de boxeo perfectos para principiantes. Solo han sido usados durante 2 meses por lo que están en condiciones muy buenas! Uno de los guantes tiene un pequeño agujero', 10, 3, 'Sports', true, false, '2019-05-15 21:14:00', '2019-05-15 21:14:00');
INSERT INTO item (owner, title, description, price, product_image, category, is_active, sold, created_at, updated_at) VALUES (-1, 'Bolso Longchamp 3D Original', 'Bolso original de la firma francesa Longchamp. Pertenece a la colección "Longchamp 3D". Es de piel de becerro con pocos tratamientos químicos, lo que le da una apariencia especialmente natural', 250, 4, 'Fashion', true, false, '2019-05-15 21:05:00', '2019-05-15 21:05:00');
INSERT INTO item (owner, title, description, price, product_image, category, is_active, sold, created_at, updated_at) VALUES (-1, 'Silla Cesta Nido', 'SILLA CESTA NIDO somos una tienda saldos&stocks, hacemos envío en toda España el precio de oferta la cesta con envío es de 154,95€ + cojín Medidas ancho 80 alto 124, aguanta 115kl', 129, 5, 'Home', true, false, '2019-05-15 21:05:00', '2019-05-15 21:05:00');
