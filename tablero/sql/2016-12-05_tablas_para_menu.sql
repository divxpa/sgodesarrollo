-- TABLA DE MENU

DROP TABLE IF EXISTS `menutablero`;
CREATE TABLE `menutablero` (
  `IdMenu` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(50) NOT NULL,
  `IdAccion` SMALLINT UNSIGNED NULL,
  `Activo` TINYINT UNSIGNED NOT NULL,
  `IdMenuPadre` SMALLINT UNSIGNED NULL,
  `Orden` SMALLINT UNSIGNED NULL,
  PRIMARY KEY (`IdMenu`));

  
  
INSERT INTO `menutablero` (`IdMenu`,`Nombre`,`IdAccion`,`Activo`,`IdMenuPadre`,`Orden`) VALUES (1,'Obras',1,1,NULL,1);
INSERT INTO `menutablero` (`IdMenu`,`Nombre`,`IdAccion`,`Activo`,`IdMenuPadre`,`Orden`) VALUES (2,'Proveedores',2,1,NULL,2);
INSERT INTO `menutablero` (`IdMenu`,`Nombre`,`IdAccion`,`Activo`,`IdMenuPadre`,`Orden`) VALUES (3,'Indicadores',3,1,NULL,3);
INSERT INTO `menutablero` (`IdMenu`,`Nombre`,`IdAccion`,`Activo`,`IdMenuPadre`,`Orden`) VALUES (4,'Home',NULL,1,NULL,4);
INSERT INTO `menutablero` (`IdMenu`,`Nombre`,`IdAccion`,`Activo`,`IdMenuPadre`,`Orden`) VALUES (5,'Cerrar Sesión',4,1,4,1);
INSERT INTO `menutablero` (`IdMenu`,`Nombre`,`IdAccion`,`Activo`,`IdMenuPadre`,`Orden`) VALUES (6,'Cambiar Contraseña',5,1,4,2);




-- TABLA DE ACCIONES

DROP TABLE IF EXISTS `acciontablero`;
CREATE TABLE `acciontablero` (
  `IdAccion` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Url` VARCHAR(100) NOT NULL,
  `Activo` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`IdAccion`),
  UNIQUE INDEX `Url_UNIQUE` (`Url` ASC),
  UNIQUE INDEX `Nombre_UNIQUE` (`Nombre` ASC));

  
 
INSERT INTO `acciontablero` (`IdAccion`,`Nombre`,`Url`,`Activo`) VALUES (1,'Tablero » Estado de Obras','tablero/obras',1);
INSERT INTO `acciontablero` (`IdAccion`,`Nombre`,`Url`,`Activo`) VALUES (2,'Tablero » Proveedores','tablero/proveedores',1);
INSERT INTO `acciontablero` (`IdAccion`,`Nombre`,`Url`,`Activo`) VALUES (3,'Tablero » Indicadores','tablero/indicadores',1);
INSERT INTO `acciontablero` (`IdAccion`,`Nombre`,`Url`,`Activo`) VALUES (4,'Cerrar Sesión','site/logout',1);
INSERT INTO `acciontablero` (`IdAccion`,`Nombre`,`Url`,`Activo`) VALUES (5,'Cambiar Contraseña','site/changepassword',1);





-- TABLA DE ROLES / ACCIONES

DROP TABLE IF EXISTS `rolacciontablero`;
CREATE TABLE `rolacciontablero` (
  `IdRol` INT UNSIGNED NOT NULL,
  `IdAccion` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`IdRol`, `IdAccion`));

  
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (1,1);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (1,2);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (1,3);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (1,4);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (1,5);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (2,1);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (2,2);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (2,3);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (2,4);
INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (2,5);


-- Obs: Tabla de Usuarios y Tabla de Roles, utilizadas las ya existentes provenientes del sistema SGO de prado





