# Cofra

Sistema de rendición de caja chica. Permite tener un seguimiento, control y generación de la documentación utilizada en los expedientes físicos de rendiciones de caja chica.

# Requisitos

* [Servidor LAMP](https://github.com/argob/arsat/wiki/C%C3%B3mo-montar-un-servidor-LAMP)
* Acceso LDAP

# Instalación

Ejecutar las siguientes lineas de comando

```bash
chmod +x web/pdf/wkhtmltopdf-amd64
chown -R apache:apache app/logs/
chown -R apache:apache app/cache/
```

Ejecutar desde la URL de la aplicación el archivo ubicado en `web/config.php`
Al hacer esto, se ejecutara un análisis de dependencias y de ser necesario le permitirá cargar la configuración del sistema.

En el caso de que algunos parámetros de conexión no se puedan definir utilizando el configurador ubicado en el `config.php`, ir al archivo `app/config/parameters.yml` y definir los datos de la base de datos, LDAP, SMTP; luego ir al archivo `app/config/config.yml` y definir el driver estableciendo en el parameters, el charset correspondiente y el @domain.

Para información mas detallada de la instalación del framwework que utiliza Cofra ir a https://symfony.com/doc/2.8/setup.html

# Contacto

Te invitamos a contactarnos en caso de que encuentres algún defecto (bug) o tengas feedback de alguna parte de cofra.

Para todo lo demás, podés mandarnos tu comentario o consulta a soporteexterno@magyp.gob.ar 
o consultas@softwarepublico.gob.ar
