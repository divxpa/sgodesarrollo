-- Ajustes de menú

INSERT INTO `menutablero` (`IdMenu`, `Nombre`, `IdAccion`, `Activo`, `Orden`) VALUES ('7', 'Volver a Inicio', '6', '1', '5');
UPDATE `menutablero` SET `Activo`='0' WHERE `IdMenu`='4';
UPDATE `menutablero` SET `IdMenuPadre`='NULL', `Orden`='6' WHERE `IdMenu`='5';


INSERT INTO `acciontablero` (`IdAccion`, `Nombre`, `Url`, `Activo`) VALUES (6, 'Volver a Inicio', 'site/root', '1');

INSERT INTO `rolacciontablero` (`IdRol`,`IdAccion`) VALUES (2,6);