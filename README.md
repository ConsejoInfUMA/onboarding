# Onboarding CEETSII
Página web hecha en PHP para importar los usuarios al servidor LDAP del CEETSII

## Conceptos
- LDAP: Lightweight Directory Access Protocol, se utiliza para tener un sistema centralizado de inicio de sesión usado en todos los servicios que ofrecemos.
- Base de datos: Generalmente MySQL/MariaDB, aquí se almacenan las invitaciones de todos los representantes.

## Instalación
Esta aplicación necesita las siguientes dependencias:
- PHP >= 8.0
    - Extensión mysqli
    - Extensión ldap
- Composer
- Base de datos (MySQL / MariaDB)
- Servidor LDAP
- Servidor SMTP

Para instalar las dependencias ejecuta el siguiente comando:
```bash
composer install
```

También es necesario ejecutar el setup de la base de datos MySQL/MariaDB,
el script `.sql` se encuentra en `misc/structure.sql`.

## Variables de entorno
Copia el archivo `.env.example` a .env y modifica los valores con los necesarios. A continuación se detalla cada campo:
| Variable | Descripción | Ejemplo |
| :--: | :--: | :--: |
| APP_URL | Url apuntando a la aplicación | http://localhost:8080/onboarding |
| LDAP_URI | URI de LDAP | ldap://127.0.0.1:3890 |
| LDAP_USERNAME | Nombre de usuario del administrador de LDAP | admin |
| LDAP_PASSWORD | Contraseña del administrador de LDAP | ... |
| LDAP_BASE | Base DN de LDAP | ou=people,dc=org,dc=es |
| CSV_COLUMN_FIRSTNAME | Nombre de la cabecera de CSV que contiene el nombre del usuario | Nombre |
| CSV_COLUMN_LASTNAME | Nombre de la cabecera de CSV que contiene los apellido(s) del usuario | Apellido(s) |
| CSV_COLUMN_EMAIL | Nombre de la cabecera de CSV que contiene el correo electrónico del usuario | Correo principal de contacto |
| MAIL_HOST | IP / Hostname del SMTP | 127.0.0.1 |
| MAIL_PORT | Puerto del SMTP | 3306 |
| MAIL_USERNAME | Nombre de usuario SMTP que enviará los correos | test@example.com |
| MAIL_PASSWORD | Contraseña del usuario SMTP que enviará los correos  | ... |
| MAIL_SECURE | Método de encriptación a usar | starttls / ssl / none |
| MAIL_FROM | Nombre mostrado al enviar correo electrónico | CEETSII |
