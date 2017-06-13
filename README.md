# Cofra

Sistema de rendición de caja chica, permite tener un seguimiento, control y generación de la documentación utilizada en los expedientes físicos de rendiciones de caja chica.

# Requisitos

Linux
PHP >= 5.4
APACHE
Acceso LDAP
Acceso a cualquier base de datos Relacional.

# Instalación

Ejecutar las siguientes lineas de comando

sudo chmod +x web/pff/wkhtmltopdf-amd64
sudo chown www-data:www-data app/logs/
sudo chown www-data:www-data app/cache/

Ejecutar desde la url de la aplicación el archivo ubicado en web/config.php
Al hacer esto, se ejecutara un análisis de dependencias y de ser necesario le permitirá cargar la configuración del sistema.

En el caso de que algunos parámetros de conexión no puedan ser definidos utilizando el configurador ubicado en el config.php, se los puede definir a mano de la siguiente manera:

Ir al archivo app/config/parameters.yml y definir los datos de la base de datos, ldap, smtp.
Ir al archivo aoo/config/config.yml y definir el driver estableciendo en el parameters, el charset correspondiente y el @domain.

Para información mas detallada de la instalación del framwework que utiliza COFRA ir a https://symfony.com/doc/2.8/setup.html

# Contacto

Te invitamos a crearnos un issue en caso de que encuentres algún bug o tengas feedback de alguna parte de cofra.

Para todo lo demás, podés mandarnos tu comentario o consulta a soporteexterno@magyp.gob.ar 
o consultas@softwarepublico.gob.ar