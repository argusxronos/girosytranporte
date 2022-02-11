SELECT*FROM bus
SELECT flota,tarjeta_habilitacion,marca,carroceria,placa_rodaje,nro_pisos,ca1ini,ca2ini,ca1fin,ca2fin,cantasientos,propietario,imagen,obs FROM bus

SELECT bus.flota,bus.tarjeta_habilitacion,bus.marca,bus.carroceria,bus.placa_rodaje,
	bus.nro_pisos,bus.ca1ini,bus.ca2ini,bus.ca1fin,bus.ca2fin,bus.cantasientos,bus.propietario,bus.imagen,bus.obs FROM bus

SELECT COUNT(bus.id_bus) AS Total FROM bus

SELECT*FROM cliente
SELECT nombres,tipo_doc,nro_documento,ruc,razon_social,nacionalidad,sexo,edad,direccion,telefono_celu,e_mail,obs,idconvenio FROM cliente
SELECT COUNT(id_cliente) AS Total FROM cliente WHERE sexo='M' AND nro_documento='19927549'

SELECT*FROM cliente WHERE nro_documento='45555555'



INSERT INTO cliente (id_cliente,nombres,tipo_doc,nro_documento,ruc,razon_social,nacionalidad,sexo,edad,direccion,telefono_celu,e_mail,obs,idconvenio)
VALUES(NULL,'richard','dni','45555555','11111111111','na','peruano','M','20','mela','98888','mela','na','1')




SELECT `g_movimiento`.`id_movimiento`, `g_movimiento`.`id_oficina_origen`, 
`CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO', 
`REMITENTE`.`per_ape_nom` AS 'REMITENTE', CASE `g_movimiento`.`esta_cancelado`
			WHEN 0 THEN 'NO'
			ELSE 'SI'
			END AS 'ESTADO', DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') AS `fecha_emision`, TIME_FORMAT(`g_movimiento`.`hora_emision`, '%r') AS `hora_emision`, 
			CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-',
RIGHT(CONCAT('00000000', CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA', CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$'), CAST(`g_movimiento`.`monto_giro` AS CHAR)) AS 'MONTO', `g_movimiento`.`id_usuario`, `g_movimiento`.`id_oficina_destino`
			FROM `g_movimiento`
			INNER JOIN `g_persona` AS `CONSIGNATARIO`
			ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
			INNER JOIN `g_persona` AS `REMITENTE`
			ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
			WHERE `g_movimiento`.`esta_anulado` = 0
			AND `g_movimiento`.`autorizado` = 0
			AND `g_movimiento`.`esta_cancelado` = 0
			AND `g_movimiento`.`de_administracion` = 0
ORDER BY `g_movimiento`.`fecha_emision` DESC, `CONSIGNATARIO`.`per_ape_nom`
LIMIT 50

			
SELECT COUNT(`g_movimiento`.`id_movimiento`) AS 'TOTAL'
			FROM `g_movimiento`
			INNER JOIN `g_persona` AS `CONSIGNATARIO`
			ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
			INNER JOIN `g_persona` AS `REMITENTE`
			ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
			WHERE `g_movimiento`.`esta_anulado` = 0
			AND `g_movimiento`.`autorizado` = 0
			AND `g_movimiento`.`esta_cancelado` = 0
			AND `g_movimiento`.`de_administracion` = 0			
			
			
			
			
			
SELECT `g_movimiento`.`id_movimiento`, `g_movimiento`.`id_oficina_origen`, `g_movimiento`.`id_oficina_destino`, `REMITENTE`.`per_ape_nom` AS 'REMITENTE', `g_movimiento`.`id_consignatario` AS 'ID_COSIGNATARIO',
`CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO', CONCAT(DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y'), ' - ', TIME_FORMAT(`g_movimiento`.`hora_emision`,'%r')) AS 'fecha_emision',
CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$'), CAST(`g_movimiento`.`monto_giro` AS CHAR) , ' ( ', `g_movimiento`.`monto_giro_letras`, ' )') AS 'MONTO',
`g_movimiento`.`forma_entrega`, `g_movimiento`.`esta_cancelado`
							FROM `g_movimiento`
							INNER JOIN `g_persona` AS `CONSIGNATARIO`
							ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
							INNER JOIN `g_persona` AS `REMITENTE`
							ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
							WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
							AND `g_movimiento`.`esta_anulado` = 0
							AND `g_movimiento`.`esta_cancelado` = 0
							LIMIT 1
							
							
							
							
SELECT*FROM oficinas					

SELECT oficinas.nro_ip,oficinas.oficina,oficinas.serie,oficinas.direccion FROM oficinas
ORDER BY oficinas.`oficina` DESC, oficinas.`idoficina` LIMIT 50
SELECT COUNT(oficinas.idoficina) AS TOTAL FROM oficinas

INSERT INTO oficinas(oficinas.idoficina,oficinas.oficina,oficinas.nro_ip,oficinas.data,oficinas.`color_red`,oficinas.`color_green`,oficinas.`color_blue`,
oficinas.`ver`,oficinas.`serie`,oficinas.`icono`,oficinas.`retrazo`,oficinas.`direccion`)VALUES()


SELECT*FROM ruta

SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,ruta.`nro_certificacion` 
FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
ORDER BY oficinas.`oficina`

SELECT COUNT(oficinas.`idoficina`) AS TOTAL FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`

INSERT INTO ruta(ruta.`id_ruta`,ruta.`destino`,ruta.`nro_certificacion`,ruta.`obs`,ruta.`idoficina`,ruta.`hora`) VALUES()

SELECT *FROM oficinas
SELECT*FROM ruta
SELECT*FROM sub_rutas

SELECT oficinas.`oficina`,ruta.`destino`,sub_rutas.`localidad`,sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`abrev`
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
ORDER BY oficinas.`oficina`

SELECT COUNT(oficinas.`idoficina`) AS Total 
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`


SELECT oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`
FROM `oficinas` AS oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
ORDER BY ruta.`destino`



SELECT DISTINCT id_ruta,oficina,destino,hora
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` 
INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
ORDER BY id_ruta ASC
 
SELECT *FROM sub_rutas ORDER BY id_sr DESC
INSERT INTO sub_rutas(id_sr,id_rutahora,localidad,precio_p1,precio_p2,abrev,principal) VALUES('','','','','','')

WHERE oficinas.`oficina`='ARRIOLA_LIMA'

SELECT *FROM

SELECT ruta.`id_ruta`,ruta.`hora`,sub_rutas.`localidad`
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina`='ARRIOLA_LIMA'

SELECT oficinas.`idoficina`,oficinas.`oficina`
FROM `oficinas` AS oficinas ORDER BY oficinas.`oficina`


SELECT*FROM proveedor
SELECT*FROM det_retencion
SELECT*FROM retencion


INSERT INTO proveedor(id_proveedor,nombres,ruc,direccion) VALUES(NULL,'','','')
UPDATE proveedor SET nombres='',ruc='',direccion='' WHERE id_proveedor=''
DELETE FROM proveedor WHERE id_proveedor=''

INSERT INTO retencion() VALUES(NULL,'','','')
UPDATE retencion SET id_proveedor='',fecha='' WHERE id_retencion=''
DELETE FROM retencion WHERE id_retencion=''

INSERT INTO det_retencion(id_det_retencion,id_retencion,tipo,serie,nro_correlativo,fecha_emision,monto_pago,importe_retenido)
VALUES(NULL,'','','','','','','')

UPDATE det_retencion SET id_retencion='',tipo='',serie='',nro_correlativo='',fecha_emision='',monto_pago='',importe_retenido='' WHERE id_det_retencion=''
DELETE FROM det_retencion WHERE id_det_retencion=''


SELECT * FROM tripulacion
WHERE apellidos_nombres='HUAYHUA CONDE ELENA'
SELECT nro_licencia,apellidos_nombres FROM tripulacion
ORDER BY apellidos_nombres ASC LIMIT 50

INSERT INTO tripulacion(id_tripulacion,apellidos_nombres,nro_licencia,obs) VALUES('','','','')

SELECT COUNT(nro_licencia) AS Total FROM tripulacion


SELECT*FROM numeracion_documento


