SELECT*FROM vale_1
SELECT*FROM tusuario




SELECT*FROM record_cliente
SELECT*FROM pasaje_pagado


SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,record_cliente.`id_cliente`,record_cliente.`fecha_viaje`,
record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva` FROM record_cliente
INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
########################################### INGRESAR DATOS A LA TABLA PASAJE_PAGADO############################################
INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia)
VALUES('','','','','','','','','','','','','','')
################################################# COGE EL ULTIMO VALOR INGRESADO############################################
SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado
#############################################################################################################################

SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia FROM pasaje_pagado
WHERE id_pasaje_pagado='6'


SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
record_cliente.`importe`,record_cliente.`id_record`,CONCAT(record_cliente.`serie_boleto`,' - ',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`


################################################CONSULTAS PARA REALIZAR VALES#########################################################
SELECT*FROM vales
INSERT INTO vales(vales.`id_vale`,vales.`motivo`,vales.`monto`,vales.`empleado`,vales.`fecha_crea`,vales.`hora_crea`,
vales.`u_crea`,vales.`agencia`)
VALUES('','','','','','','','')

SELECT MAX(id_vale) AS id FROM vales

SELECT monto,fecha_crea,agencia,motivo,empleado,u_crea FROM vales
WHERE id_vale='4'
######################################SUMA MONTOS#######################################################################
SELECT FORMAT(SUM(monto),2)  FROM vales

########################################################################################################################
SELECT*FROM pasaje_pagado WHERE nro_pasaje='77-559472'


SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
record_cliente.`importe`,record_cliente.`id_record`,
CONCAT(record_cliente.`serie_boleto`,' - ',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
WHERE record_cliente.`serie_boleto`='101' AND record_cliente.`numero_boleto`='309231'

SELECT*FROM record_cliente WHERE serie_boleto='77' AND numero_boleto='559469'

SELECT record_cliente.`tipo_reserva` FROM record_cliente
INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
WHERE record_cliente.`serie_boleto`='101' AND record_cliente.`numero_boleto`='309231'













