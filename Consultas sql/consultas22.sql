SELECT*FROM salida
SELECT*FROM ruta
SELECT*FROM sub_rutas
SELECT*FROM oficinas
SELECT*FROM bus
SELECT*FROM destino_enc
SELECT*FROM tripulacion
SELECT*FROM tsistema
SELECT*FROM tsistema_tusuario
SELECT*FROM lista_documentos
SELECT*FROM tusuario ORDER BY id_usuario ASC

----------------------------consultas para salidas-------------------------------------------
SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`
FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina`='HUANCAYO-PRINCIPAL'
ORDER BY salida.`fecha` DESC

SELECT COUNT(id_salida) AS TOTAL
FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina`='HUANCAYO-PRINCIPAL'
--------------------------------------*---------------------------*---------------------------
------------------------------------consulta de oficina por cada destino---------------------------------
SELECT oficinas.`oficina`,ruta.`destino`,sub_rutas.`localidad`,sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`abrev`
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`

---------------------------------------*-------------------------------*----------------------------------
---------------------------------------colsultas de serie con documentos-----------------------------------
SELECT `nc`.`serie`, (`nc`.`numero_actual` + 1) AS `numero_actual`
FROM `numeracion_documento` AS `nc`
WHERE `nc`.`idoficina` = '1'
AND (`nc`.`tipo_operacion` = 'BOLETA'
OR `nc`.`tipo_operacion` = 4)
AND `nc`.`id` = '4'

SELECT `tusuario`.`id_usuario`, `tusuario`.`t_usuario`
FROM `tusuario`
WHERE `tusuario`.`idoficina` = '1'
AND (`tusuario`.`c_esta_activo` = 1
OR `tusuario`.`c_esta_activo` = 4)

-----------------------------------*------------*--------------------------*--------------------------------

--------------------------------------consulta de numeracion de documentos----------------------------------
SELECT*FROM documento_enc
SELECT*FROM numeracion_documento ORDER BY serie ASC
SELECT COUNT(id) FROM numeracion_documento
SELECT*FROM lista_documentos
SELECT*FROM oficinas

SELECT numeracion_documento.`serie`,numeracion_documento.`numero_actual` AS NroDocumento,lista_documentos.`documento`,
oficinas.`oficina` AS PC, lista_documentos.`detalle`,oficinas.`oficina`
FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`
ORDER BY numeracion_documento.`serie` DESC

SELECT COUNT(id) AS TOTAL
FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`


--------------------------------------Consulta de oficina por ruta--------------------------------------------

SELECT DISTINCT oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,id_ruta
FROM oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
WHERE oficinas.`idoficina` = '1'

SELECT*FROM oficinas
SELECT*FROM ruta


SELECT DISTINCT oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,id_ruta
FROM oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
WHERE oficinas.`idoficina` = '1'

----------------------------------------------Consulta para que muestre los datos de destino---------------------------------
SELECT DISTINCT oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,id_ruta
FROM oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
WHERE ruta.`id_ruta`='2'
SELECT `nc`.`serie`, (`nc`.`numero_actual` + 1) AS `numero_actual`
FROM `numeracion_documento` AS `nc`
WHERE `nc`.`idoficina` = '".$IDOFICINA."'
AND (`nc`.`tipo_operacion` = ".$TYPE."
OR `nc`.`tipo_operacion` = 4)
AND `nc`.`id` = ".$id.";

SELECT id_bus,flota,marca,placa_rodaje FROM bus ORDER BY flota ASC


---------------------------------------Consultas de ejemplo------------------------------------------------------
SELECT*FROM tusuario
SELECT*FROM temp_mov_detalle
SELECT*FROM temp_liq_detalle

SELECT `temp_mov_detalle`.`md_cantidad`
, `temp_mov_detalle`.`md_descripcion`
, `temp_mov_detalle`.`temp_item`
, `temp_mov_detalle`.`md_flete`
, `temp_mov_detalle`.`md_carrera`
, (`temp_mov_detalle`.`md_flete`* `temp_mov_detalle`.`md_cantidad`+  `temp_mov_detalle`.`md_carrera`) AS 'IMPORTE'
FROM `temp_mov_detalle`


---------------------------------------Consultas para guardar las salidas------------------------------------------
SELECT*FROM salida ORDER BY id_salida DESC 
SELECT*FROM ruta

SELECT DISTINCT ruta.`id_ruta`,salida.`id_rutahora`,oficinas.`oficina`,ruta.`hora`
FROM ruta INNER JOIN salida ON salida.`id_ruta`=ruta.`id_ruta`
INNER JOIN oficinas ON oficinas.`idoficina`=ruta.`idoficina`
WHERE salida.`id_salida`='16233245'

INSERT INTO salida(salida.`id_salida`,salida.`fecha`,salida.`id_ruta`,salida.`id_rutahora`,salida.`idoficina`,salida.`hora`,salida.`id_bus`,
salida.`cant_tripulacion`,salida.`cant_pasajeros`,salida.`cant_asientos`)VALUES('','','','','','','','','','')

-----------------------------------------------------Consultas para Numeracion-------------------------------------------
SELECT*FROM vale_1
SELECT*FROM documento_enc
SELECT*FROM tmp_detalle_enc
SELECT*FROM numeracion_documento
SELECT descripcion_documento FROM numeracion_documento WHERE id_documento='1'
SELECT*FROM lista_documentos

SELECT id_lista_documento,documento FROM lista_documentos

INSERT INTO numeracion_documento(numeracion_documento.`id`,numeracion_documento.`idoficina`,numeracion_documento.`id_documento`,
numeracion_documento.`descripcion_documento`,numeracion_documento.`serie`,numeracion_documento.`numero_actual`,numeracion_documento.`pc`,
numeracion_documento.`detalle`,numeracion_documento.`editable`,numeracion_documento.`tipo_operacion`)
VALUES(NULL,'1','2','','123','123','pc','pa','null','2')

SELECT * FROM tusuario
WHERE c_login='rmatos'





