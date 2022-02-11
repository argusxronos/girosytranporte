SELECT*FROM configuracion_bus WHERE id_bus='90' AND piso='1';

SELECT*FROM record_cliente
salida,cliente,tusuario,idoficina,id_turno;

SELECT*FROM turnos;

SELECT *
FROM `salida`
ORDER BY 1 DESC;


SELECT `salida`.`id_salida`
, `configuracion_bus`.`fila`
, `configuracion_bus`.`PISO`
, `configuracion_bus`.`n1`
, `configuracion_bus`.`n2`
, `configuracion_bus`.`n3`
, `configuracion_bus`.`n4`
, `configuracion_bus`.`n5`
FROM `salida`
INNER JOIN `bus`
ON `salida`.`id_bus` = `bus`.`id_bus`
INNER JOIN `configuracion_bus`
ON `salida`.`id_bus` = `configuracion_bus`.`id_bus`
WHERE `salida`.`id_salida` = '13603'
ORDER BY `salida`.`id_salida` DESC, `flota`, `piso` ASC , `fila` ASC;



SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida,oficinas.`idoficina`,bus.`id_bus`
FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`;



SELECT*FROM bus WHERE id_bus='115'
SELECT id_bus AS 'ID' FROM bus WHERE flota='432'

INSERT INTO configuracion_bus(configuracion_bus.`id_confbus`,configuracion_bus.`id_bus`,configuracion_bus.`fila`,
configuracion_bus.`piso`,configuracion_bus.`n1`,configuracion_bus.`n2`,configuracion_bus.`n3`,configuracion_bus.`n4`,configuracion_bus.`n5`)
VALUES('','114','1','1','1','2','','3','4')

SELECT*FROM record_cliente WHERE fecha='2012-01-24' AND destino_boleto='HUANCAYO' AND origen_boleto='AYACUCHO' AND id_salida='17847'
SELECT*FROM vale_1
#########################Consulta para mostrar datos de las tablas con las que se relaciona RECORD_CLIENTE#####################################
SELECT*FROM record_cliente WHERE fecha='2012-07-19'
SELECT*FROM tusuario

SELECT salida.`id_salida`,cliente.`id_cliente`,tusuario.`id_usuario`, oficinas.`idoficina`,turnos.`id_turno` FROM record_cliente 
INNER JOIN salida ON record_cliente.`id_salida`=salida.`id_salida` 
INNER JOIN cliente ON record_cliente.`id_cliente`=cliente.`id_cliente` 
INNER JOIN tusuario ON record_cliente.`id_usuario`=tusuario.`id_usuario` 
INNER JOIN oficinas ON record_cliente.`idoficina`=oficinas.`idoficina`
INNER JOIN turnos ON record_cliente.`id_turno`=turnos.`id_turno`
##############################################CONSULTA PARA GUARDAR DATOS EN LA TABLA RECORD_CLIENTE########################################################
INSERT INTO record_cliente (record_cliente.`id_record`,record_cliente.`id_salida`,record_cliente.`id_cliente`,record_cliente.`estado`,record_cliente.`fecha`,
record_cliente.`hora`,record_cliente.`piso`,record_cliente.`asiento`,record_cliente.`fecha_viaje`,record_cliente.`hora_viaje`,record_cliente.`piso_boleto`,
record_cliente.`asiento_boleto`,record_cliente.`serie_boleto`,record_cliente.`numero_boleto`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,
record_cliente.`importe`,record_cliente.`descuento`,record_cliente.`importe_letras`,record_cliente.`tipo_postergado`,record_cliente.`anulado`,record_cliente.`adelanto`,
record_cliente.`nro_ticket`,record_cliente.`tipo_reserva`,record_cliente.`nro_copias`,record_cliente.`obs`,record_cliente.`id_usuario`,record_cliente.`idoficina`,
record_cliente.`idhostname`,record_cliente.`hora_reserva`,record_cliente.`credito`,record_cliente.`bk_fecha_viaje`,record_cliente.`bk_origen_destino`,
record_cliente.`n_forma_pago`,record_cliente.`t_forma_pago`,record_cliente.`id_turno`,record_cliente.`n_reserva_otra_agencia`,record_cliente.`n_impresion`,
record_cliente.`u_crea`,record_cliente.`d_crea`,record_cliente.`h_crea`,record_cliente.`u_edita`,record_cliente.`d_edita`,record_cliente.`h_edita`,
record_cliente.`u_anula`,record_cliente.`d_anula`,record_cliente.`h_anula`,record_cliente.`serie_boleto_liq`,record_cliente.`numero_boleto_liq`,
record_cliente.`liquidacion_pagada`)VALUES(NULL,'','','','','','','','','','','','','','','','','','','','','','',
'','','','','','','','','','','','','','','','','','','','','','','','',
'','','',)

################################################################################################################################################

SELECT id_salida,estado,piso,asiento,
SELECT*FROM salida
SELECT*FROM destino_enc
SELECT*FROM turnos
SELECT *FROM cliente WHERE nro_documento='22222222'
 
SELECT*FROM record_cliente WHERE fecha='2012-07/27' AND piso='2' AND id_salida='16230417' ORDER BY numero_boleto 
SELECT estado,fecha,piso,asiento,serie_boleto,numero_boleto,hora FROM record_cliente WHERE id_salida='16233585'
SELECT * FROM record_cliente WHERE id_salida='16233585' AND piso='2'

SELECT id_sr,localidad, precio_p1,precio_p2 FROM sub_rutas WHERE id_rutahora='8'
SELECT*FROM sub_rutas

SELECT DISTINCT ruta.`id_ruta`,oficinas.`oficina`,ruta.`destino`,ruta.`hora` 
FROM oficinas INNER JOIN ruta ON ruta.`idoficina`=oficinas.`idoficina`
ORDER BY oficinas.`oficina` DESC
WHERE id_rutahora='8'

##############################################MODIFICAR EN NUMERO DE BOLETO POR SERIE###########################################################
SELECT id,serie,numero_actual FROM numeracion_documento WHERE idoficina=1 AND id_documento=1 AND serie=49
UPDATE numeracion_documento SET numero_actual='888332' WHERE serie='49'
################################################################################################################################################
SELECT*FROM record_cliente WHERE fecha='2012-08-03'

########################################MOSTRAR LOS VALORES DE CADA OFICINA SEGUN EL RECORD CLIENTE#############################################
SELECT DISTINCT record_cliente.`idoficina`,oficinas.`oficina`,estado,asiento
FROM oficinas INNER JOIN record_cliente ON record_cliente.`idoficina`=oficinas.`idoficina`
WHERE id_salida='16230552' AND estado=1
################################################################################################################################################

SELECT*FROM oficinas WHERE idoficina=7
SELECT * FROM oficinas
SELECT*FROM numeracion_documento WHERE descripcion_documento='BOLETA' AND serie='163'

SELECT*FROM configuracion_bus WHERE id_bus='25' ORDER BY piso
SELECT piso,asiento FROM record_cliente WHERE id_salida='16233685'
SELECT estado,asiento FROM record_cliente WHERE id_salida='16233685'

SELECT id,serie,numero_actual FROM numeracion_documento WHERE idoficina='1' AND id_documento=1 AND serie='163'
SELECT COUNT(id_cliente) AS Total de Clientes FROM record_cliente WHERE fecha_viaje='2012-07-19'