
-----------------------------------------Consultas para eliminacion de datos------------------------------------------------
SELECT*FROM salida
SELECT*FROM ruta
DELETE FROM salida WHERE id_salida='2471'

SELECT salida.`idoficina`,oficinas.`oficina`,salida.`fecha`,salida.`id_ruta`,ruta.`destino`,ruta.`hora`,
salida.`id_bus`,bus.`flota`,bus.`placa_rodaje`,salida.`id_salida` FROM salida
INNER JOIN bus ON bus.`id_bus`=salida.`id_bus`
INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
WHERE salida.`id_salida`='16233259'

DELETE FROM salida WHERE id_salida='1'

---------------------------------------------Consulta para editar salidas------------------------------------------------------
SELECT*FROM salida WHERE salida.id_salida='16233259'
UPDATE retencion SET salida.`fecha`='',salida.`id_ruta`='',salida.`id_rutahora`='',salida.`idoficina`='',salida.`hora`='',
salida.`id_bus`='' WHERE id_salida=''

UPDATE salida SET salida.`fecha`='2012-06-14',salida.`id_ruta`='9',salida.`id_rutahora`='9',
salida.`idoficina`='1',salida.`hora`='10',salida.`id_bus`='15' WHERE id_salida='16233265'

------------------------------------------------Eliminar--Modificar oficinas--------------------------------------------------------
SELECT oficinas.nro_ip,oficinas.oficina,oficinas.serie,oficinas.direccion,oficinas.`idoficina` FROM oficinas
DELETE FROM oficinas WHERE oficinas.`idoficina`='$valor'

SELECT oficinas.`idoficina`,oficinas.`nro_ip`,oficinas.`serie`,
oficinas.`oficina`,oficinas.`direccion` FROM oficinas WHERE oficinas.`idoficina`='1'

UPDATE oficinas SET oficinas.`nro_ip`='',oficinas.`serie`='',oficinas.`oficina`='',oficinas.`direccion`='' 
WHERE oficinas.`idoficina`=''

---------------------------------------------------Eliminar--Modificar--Buscar Rutas-----------------------------------------------------------------
SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,ruta.`nro_certificacion`,ruta.`id_ruta`
FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina` LIKE '%Huancayo%'

SELECT COUNT(oficinas.`idoficina`) AS TOTAL 
FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina` LIKE '%Huancayo%'

SELECT ruta.`id_ruta`,oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,ruta.`nro_certificacion`,ruta.`obs` FROM ruta 
INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE ruta.`id_ruta`='1'

SELECT*FROM ruta
UPDATE ruta SET ruta.`destino`='',ruta.`nro_certificacion`='',ruta.`obs`='',ruta.`idoficina`='',ruta.`hora`=''
WHERE ruta.`id_ruta`=''

----------------------------------------------------Eliminar--Modificar--Buscar Sub Rutas---------------------------------------------------------------
SELECT*FROM sub_rutas
SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,sub_rutas.`localidad`,sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`id_sr`
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`

DELETE FROM sub_rutas WHERE sub_rutas.`id_sr`=''

SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,sub_rutas.`localidad`,sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`id_sr`
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina` LIKE '%%'

SELECT COUNT(oficinas.`idoficina`) AS TOTAL 
FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE oficinas.`oficina` LIKE'%huancayo%'

SELECT sub_rutas.`id_sr`,ruta.`id_ruta`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,sub_rutas.`localidad`,sub_rutas.`principal`,
sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`abrev` FROM sub_rutas
INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta`
INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
WHERE sub_rutas.`id_sr`='298'

SELECT*FROM sub_rutas
UPDATE sub_rutas SET sub_rutas.`id_rutahora`='',sub_rutas.`localidad`='',sub_rutas.`precio_p1`='',
sub_rutas.`precio_p2`='',sub_rutas.`principal`=''
WHERE sub_rutas.`id_sr`=''

----------------------------------------------------Eliminar--Modificar--Buscar Tripulantes--------------------------------------------------------
SELECT*FROM tripulacion


DELETE FROM tripulacion WHERE tripulacion.`id_tripulacion`=''

SELECT id_tripulacion,apellidos_nombres,nro_licencia,obs
FROM tripulacion
WHERE tripulacion.`id_tripulacion`=''

UPDATE tripulacion SET tripulacion.`apellidos_nombres`='',tripulacion.`nro_licencia`='',
tripulacion.`obs`='' 
WHERE tripulacion.`id_tripulacion`=''

-----------------------------------------------------Eliminar--Modificar--Buscar Numeraciones-----------------------------------------------------
SELECT*FROM numeracion_documento WHERE numeracion_documento.id='394'

SELECT numeracion_documento.`id`,oficinas.`idoficina`,oficinas.`oficina`,lista_documentos.`id_lista_documento`,lista_documentos.`documento`,
numeracion_documento.`serie`,numeracion_documento.`numero_actual` AS NroDocumento,oficinas.`oficina` AS PC, 
lista_documentos.`detalle`,numeracion_documento.`tipo_operacion`
FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`
WHERE numeracion_documento.`id`=''

DELETE FROM numeracion_documento WHERE numeracion_documento.id='1'

UPDATE numeracion_documento SET numeracion_documento.`idoficina`='17',numeracion_documento.`id_documento`='1',
numeracion_documento.`descripcion_documento`='BO',numeracion_documento.`serie`='12',numeracion_documento.`numero_actual`='456',
numeracion_documento.`pc`='RICHARD',numeracion_documento.`detalle`='PASJAES',numeracion_documento.`tipo_operacion`='0'
WHERE numeracion_documento.`id`='3'

-----------------------------------------------------Eliminar--Modificar--Buscar Buses--------------------------------------------------------------
SELECT bus.flota,bus.tarjeta_habilitacion,bus.marca,bus.carroceria,bus.placa_rodaje,
bus.nro_pisos,bus.ca1ini,bus.ca2ini,bus.ca1fin,bus.ca2fin,bus.cantasientos,bus.propietario,bus.imagen,bus.obs, bus.`id_bus` 
FROM bus WHERE id_bus="28"

SELECT*FROM bus WHERE id_bus='113'
UPDATE bus SET bus.`flota`='',bus.`tarjeta_habilitacion`='',bus.`marca`='',bus.`carroceria`='',
bus.`placa_rodaje`='',bus.`nro_pisos`='',bus.`ca1ini`='',bus.`ca2ini`='',bus.`ca1fin`='',
bus.`ca2fin`='',bus.`cantasientos`='',bus.`propietario`='',bus.`imagen`='',bus.`obs`
bus.`usuario_creacion`='',bus.`fecha_creacion`=CURDATE(),bus.`hora_creacion`=CURRENT_TIME()
WHERE bus.`id_bus`=''

DELETE FROM bus WHERE id_bus=''

//SUBIR UNA IMÁGEN A UNA CARPETA ESPECÍFICA Y GUARDAR EL NOMBRE EN UNA VARIABLE 

  $destino = 'imagenes/' ;  
  move_uploaded_file ( $_FILES [ 'file' ][ 'tmp_name' ], $destino . '/' . $_FILES [ 'file' ][ 'name' ]);  
  $NAME = ($_FILES['file']['name']);//ASí obtienes el nombre de la imágen 


     
    $image = imagecreatefromjpeg('imagenes/'.$NAME); 
    ob_start(); 
    imagejpeg($image); 
    $jpg = ob_get_contents(); 
    ob_end_clean(); 

    //introducir la imágen   
    
    $jpg = str_replace('##','\#\#',mysql_escape_string($jpg)); 
     
    $result = mysql_query("insert into imagenes(nombre,imagen) values ('$name','$jpg')");
    
------------------------------------------------------ Copiar Salidas de Buses----------------------------------------------------------------
SELECT *FROM salida WHERE fecha='2012-06-20' AND idoficina='16'

SELECT*FROM salida WHERE fecha='2012-08-11'

SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida,oficinas.`idoficina`
FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
WHERE  oficinas.`idoficina`='23'

SELECT*FROM tusuario
SELECT*FROM tsistema_tusuario
SELECT*FROM configuracion_bus
SELECT*FROM numeracion_documento WHERE id='390'
---------------------------------------------validar que nose pueda validar salidas que tengan pasajeros a bordo----------------------------------
SELECT*FROM configuracion_bus
SELECT COUNT(id_salida) FROM record_cliente WHERE id_salida='16694'
SELECT*FROM salida WHERE id_salida='16268'
SELECT*FROM record_cliente WHERE id_salida='16268'
DELETE FROM salida WHERE id_salida='16268'

###################################################################################################################################################


SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida
FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
WHERE  salida.`fecha`=CURDATE() AND salida.`idoficina`='1'


SELECT*FROM salida
