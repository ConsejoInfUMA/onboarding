# Onboarding CEETSII
Página web hecha en PHP para importar los usuarios al servidor LDAP del CEETSII

## Instalación
Esta aplicación necesita las siguientes dependencias:
- PHP >= 8.0
    - Extensión mysqli
    - Extensión ldap
- Composer
- MySQL / MariaDB
- Servidor LDAP
- Servidor SMTP

Para instalar las dependencias ejecuta el siguiente comando:
```bash
composer install
```

También es necesario ejecutar el setup de la base de datos MySQL/MariaDB,
el script `.sql` se encuentra en `misc/structure.sql`.
