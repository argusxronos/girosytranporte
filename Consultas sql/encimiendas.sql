
SELECT*FROM e_movimiento

SELECT*FROM record_cliente WHERE fecha_viaje LIKE '%2012-07-19%'

SELECT id_salida,COUNT(id_salida) AS numero FROM record_cliente WHERE fecha_viaje='2012-07-19' AND IF(COUNT(id_salida)<= 20)
GROUP BY id_salida



SELECT id_salida, IF(COUNT(id_salida) <= 20, 'bien', 'mal')AS condicion,COUNT(id_salida) FROM record_cliente WHERE fecha_viaje='2012-07-19'
GROUP BY id_salida

SELECT id_salida,hora_viaje,CONCAT(serie_boleto,'-',numero_boleto) AS Boleto,CONCAT(piso,'-',asiento) AS PisoAsiento,origen_boleto,destino_boleto,cliente.`nombres` FROM record_cliente
INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente` WHERE record_cliente.`fecha_viaje`='2012-07-19'
ORDER BY record_cliente.`id_salida`,record_cliente.`piso_boleto`,record_cliente.`asiento_boleto`



########################################mostrar encomiendas por agencia###############################################################
SELECT
`e_movimiento`.`id_oficina_origen`
, CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
, IF(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`) 
AS `CONSIGNATARIO`
, CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
, ' '
, `e_mov_detalle`.`md_descripcion`) AS CHAR) AS 'DESCRIPCION'
, `e_mov_detalle`.`md_estado`
FROM `e_movimiento`
INNER JOIN `e_persona`
ON `e_movimiento`.`id_consignatario` = `e_persona`.`id_persona`
INNER JOIN `e_mov_detalle`
ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
INNER JOIN `e_md_operacion`
ON `e_md_operacion`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
AND `e_md_operacion`.`e_num_item` = `e_mov_detalle`.`e_num_item`
WHERE `e_movimiento`.`id_oficina_destino` = "1"
AND `e_md_operacion`.`mdo_fecha` = '2012-07-03'
AND `e_md_operacion`.`tipo_operacion` = 1
AND `e_mov_detalle`.`md_estado` = 3
ORDER BY `e_movimiento`.`id_oficina_origen`
, `e_movimiento`.`num_serie` ASC
, `e_movimiento`.`num_documento` ASC

#############################################################FIN######################################################
#############################################################CONSULTA DE GIROS ENVIADOS######################################################
SELECT `g_movimiento`.`id_movimiento`, `g_movimiento`.`id_oficina_origen`, 
`CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO', 
`REMITENTE`.`per_ape_nom` AS 'REMITENTE', CASE `g_movimiento`.`esta_cancelado`
			WHEN 0 THEN 'NO'
			ELSE 'SI'
			END AS 'ESTADO', DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') AS `fecha_emision`, TIME_FORMAT(`g_movimiento`.`hora_emision`, '%r') AS `hora_emision`, 
			CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-',
RIGHT(CONCAT('00000000', CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA', 
CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$'), CAST(`g_movimiento`.`monto_giro` AS CHAR)) AS 'MONTO', `g_movimiento`.`id_usuario`, `g_movimiento`.`id_oficina_destino`
			FROM `g_movimiento`
			INNER JOIN `g_persona` AS `CONSIGNATARIO`
			ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
			INNER JOIN `g_persona` AS `REMITENTE`
			ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
			WHERE `g_movimiento`.`esta_anulado` = 0
			AND `g_movimiento`.`esta_cancelado` = 0
			ORDER BY `g_movimiento`.`fecha_emision` DESC, `CONSIGNATARIO`.`per_ape_nom`
LIMIT 30;
#############################################################FIN######################################################

############################################ BSCAR ENCOMIENDAS PARA MODIFICAR CLAVE##########################
SELECT `e_movimiento`.`id_movimiento`,`e_movimiento`.`id_oficina_origen`,`CONSIGNATARIO`.`per_nombre` AS 'CONSIGNATARIO',
`REMITENTE`.`per_nombre` AS 'REMITENTE', CASE `DETALLE`.md_estado WHEN 3 THEN 'NO' ELSE 'SI' END AS 'ESTADO',
DATE_FORMAT(e_movimiento.`e_fecha_emision`,'%d/%m/%Y') AS fecha_emision, TIME_FORMAT(e_movimiento.`e_hora_emision`,'%r') AS hora_emision,
CONCAT(RIGHT(CONCAT('0000', CAST(e_movimiento.`num_serie` AS CHAR)),4), '-',
RIGHT(CONCAT('00000000',CAST(e_movimiento.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA',e_movimiento.`e_documento`,
e_movimiento.`id_usuario`,e_movimiento.`id_oficina_destino`,`REMITENTE`.`per_razon_social`
FROM `e_movimiento` 
INNER JOIN `e_persona` AS `CONSIGNATARIO` ON `e_movimiento`.`id_consignatario`= `CONSIGNATARIO`.`id_persona`
INNER JOIN `e_persona` AS `REMITENTE` ON `e_movimiento`.`id_remitente`= `REMITENTE`.`id_persona`
INNER JOIN e_mov_detalle AS `DETALLE` ON e_movimiento.`id_movimiento`= `DETALLE`.`id_movimiento`

WHERE `DETALLE`.md_estado=3 AND e_movimiento.`id_movimiento`=''
ORDER BY e_movimiento.`e_fecha_emision` DESC, `CONSIGNATARIO`.per_nombre LIMIT 1

WHERE `DETALLE`.md_estado=3 AND e_movimiento.`num_serie`='49'
AND `CONSIGNATARIO`.per_nombre LIKE '%ADAUTO%'
ORDER BY e_movimiento.`e_fecha_emision` DESC, `CONSIGNATARIO`.per_nombre LIMIT 50

SELECT COUNT(`e_movimiento`.`id_movimiento`) AS 'EXISTE'
FROM `e_movimiento`
WHERE `e_movimiento`.`id_movimiento` = "128428"

SELECT*FROM e_persona
SELECT*FROM e_mov_detalle WHERE id_movimiento='128427'
SELECT*FROM e_movimiento WHERE id_movimiento='128427'
SELECT*FROM e_operacion

SELECT COUNT(`g_movimiento`.`id_movimiento`) AS 'CANCELADO'
FROM `g_movimiento`
WHERE `g_movimiento`.`id_movimiento` = "10"
AND `g_movimiento`.`esta_cancelado` = 1


SELECT*FROM e_persona







SELECT `e_movimiento`.`id_movimiento`
, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `fecha_emision`
, TIME_FORMAT(`e_movimiento`.`e_hora_emision`, '%r') AS `hora_emision`
, CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`)
AS `CONSIGNATARIO`
, IF(`REMIT`.`per_tipo` = 'PERSONA', IFNULL(`REMIT`.`per_nombre`,'TURISMO CENTRAL'), IFNULL(`REMIT`.`per_razon_social`,'TURISMO CENTRAL'))
AS `REMITENTE`
, `e_movimiento`.`id_oficina_origen`
, `e_movimiento`.`id_usuario`
FROM `e_movimiento`
INNER JOIN `e_persona` AS `CONSIG`
ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
LEFT JOIN `e_persona` AS `REMIT`
ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
INNER JOIN `e_mov_detalle`
ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
WHERE `e_movimiento`.`id_oficina_destino` = "1"
AND `e_movimiento`.`e_fecha_emision` <= CURDATE()
AND `e_mov_detalle`.`md_estado` = 3










