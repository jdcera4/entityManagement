# Prueba tecnica

## Requerimientos
- Servidor Apahce o Ngnix
- PHP >= 8.1, con las siguientes extensiones:
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MariaDB Version 15.1
- Composer >= 2.2

# Proceso de ejecución

1. Una vez descargue el repositorio desde [TalentPitch](https://github.com/sebaris/talentPitch.git)
2. Ubíquese en la carpeta donde realizo el clone
3. Ejecute:
```bash
composer install
```
4. Cree el archivo .env para parametrizar la configuración de la conexión a la base de datos:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talent-pich
DB_USERNAME=user
DB_PASSWORD=pass
```
Cambie el user y pass por la configuración que tenga en su local o servidor, así como puede modificar cualquiera de los parámetros acá presentes.

5. Ejecute el siguiente comando para migrar la estructura de tablas en la base de datos:
```
php artisan migrate --seed
```

Este comando también nos creará un usuario en base de datos para posterior autentificación de las APIs, consulte entonces la tabla y tome el correo electrónico para el cual la contraseña será password. Este será usado en pasos siguientes.

6. Inicialice el servidor, si esta en su local, con el siguiente comando:
```
php artisan serve
```

7. Para hacer el llenado de las tablas en base de datos puede hacer uso del siguiente POST:

Antes de la ejecución ingrese al archivo config/services.php y ajuste el parámetro de la key en el array gpt, es posible que GPT la remueva por seguridad, contácteme para entregar un key para test o genere una propia clave en [OpenAI](https://platform.openai.com/api-keys)

```json
curl --location 'http://127.0.0.1:8000/api/process-gpt?model=user'
```
Cargue en Postman y el parámetro “model” toma cualquiera de los siguientes valores: users/companies/challenges/programs según la tabla que desee llenar.

Este servicio conecta a API de GTP, para generar datos aleatorios.

8. Una vez llenada de información las tablas, se procede a usar la siguiente API de autentificación:
```json
curl --location 'http://127.0.0.1:8000/api/login' \
--form 'email="cerdev@example.net"' \
--form 'password="password"'
```
Donde se modifica el email y password por los tomados en el punto 6.

9. La anterior API nos genera una respuesta con la siguiente estructura:
```json
{
    "user": [
        {
            "id": 1,
            "name": "Mr. Antwon Donnelly DDS",
            "email": "cerdev@example.net",
            "image_path": null,
            "email_verified_at": "2024-01-23T23:06:42.000000Z",
            "password": "$2y$12$Ng1TMSIDw3OTs8wdTzHdnOQmFwha5RjKNgBhU1PIKsEn2GVFc6K16",
            "created_at": "2024-01-23T23:06:42.000000Z",
            "updated_at": "2024-01-23T23:06:42.000000Z"
        }
    ],
    "token": "6|bSlXDWQxotPeg6RajjGDjXPqUGBMLFNrJH8Kxgzr82937a58",
    "message": "Success"
}
```
De esta respuesta tomamos el token, para posterior autentificación usando Bearer de las CRUDS creados para las APIs.

----------------------

## Tiene entonces a disposición las siguientes API's

## User
- GET {baseUrl}/v1/users?page=1&items=10.

page: el número de pagina que se desea mostrar y items es opcional si desea variar el número de items por página

- GET {baseUrl}/v1/users/{id}

id: Identificador del usuario que desea consultar

- POST {baseUrl}/v1/users

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "name" : "David Cera",
    "email": "david.cera@example.com",
    "image_path": null,
    "password": "admin123"
}
```

- PUT {baseUrl}/v1/users/{id}

id: Identificador del usuario que desea actualizar

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "name" : "David Cera",
    "email": "david.cera@example.com",
    "image_path": null,
    "password": "admin123"
}
```

- DELETE {baseUrl}/v1/users/{id}

id: Identificador del usuario que desea eliminar

--------------------------

## Challenges
- GET {baseUrl}/v1/challenges?page=1&items=10.

page: el número de pagina que se desea mostrar y items es opcional si desea variar el número de items por página, si no por defecto es 10

- GET {baseUrl}/v1/challenges/{id}

id: Identificador del challenge que desea consultar

- POST {baseUrl}/v1/challenges

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "title" : "Desafio Muy Nuevo",
    "description": "Desafio muy desafiante para todo mundo",
    "difficulty" : 9,
    "user_id": 1
}
```

- PUT {baseUrl}/v1/challenges/{id}

id: Identificador del challenge que desea actualizar

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "title" : "Desafio Muy Nuevo",
    "description": "Desafio muy desafiante para todo mundo",
    "difficulty" : 9,
    "user_id": 1
}
```

- DELETE {baseUrl}/v1/challenges/{id}

id: Identificador del challenge que desea eliminar

-----------

## Companies
- GET {baseUrl}/v1/companies?page=1&items=10.

page: el número de pagina que se desea mostrar y items es opcional si desea variar el número de items por página, si no por defecto es 10

- GET {baseUrl}/v1/companies/{id}

id: Identificador del company que desea consultar

- POST {baseUrl}/v1/companies

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "name" : "Compañia muy grande",
    "image_path": null,
    "location" : "En algun lugar del mundo cerca",
    "industry": "Salud",
    "user_id": 6
}
```

- PUT {baseUrl}/v1/companies/{id}

id: Identificador del company que desea actualizar

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "name" : "Compañia muy grande",
    "image_path": null,
    "location" : "En algun lugar del mundo cerca",
    "industry": "Salud",
    "user_id": 6
}
```

- DELETE {baseUrl}/v1/companies/{id}

id: Identificador del company que desea eliminar

----------------------------

## Programs
- GET {baseUrl}/v1/programs?page=1&items=10.

page: el número de pagina que se desea mostrar y items es opcional si desea variar el número de items por página, si no por defecto es 10

- GET {baseUrl}/v1/programs/{id}

id: Identificador del program que desea consultar

- POST {baseUrl}/v1/programs

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "title": "Nuevo Programato.",
    "description": "Programa de capacitación muy bueno y muy exigente",
    "start_date": "2024-01-01",
    "end_date": "2024-11-30",
    "user_id": 1
}
```

- PUT {baseUrl}/v1/programs/{id}

id: Identificador del program que desea actualizar

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "title": "Nuevo Programato.",
    "description": "Programa de capacitación muy bueno y muy exigente",
    "start_date": "2024-01-01",
    "end_date": "2024-11-30",
    "user_id": 1
}
```

- DELETE {baseUrl}/v1/programs/{id}

id: Identificador del program que desea eliminar

------------------

## Programs Participants
- GET {baseUrl}/v1/programs-participants?page=1&items=10.

page: el número de pagina que se desea mostrar y items es opcional si desea variar el número de items por página, si no por defecto es 10

- GET {baseUrl}/v1/programs-participants/{id}

id: Identificador del registro que desea consultar

- POST {baseUrl}/v1/programs-participants

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "program_id": 9,
    "entity_id": 9,
    "entity_type": "challenge"
}
```

entity_type es un enum que solo le recibirá valores cómo: user, challange, program, se debe tener presente el valor enviado en entity_id, puesto que se valida que el identificador exista en la entidad enviada.

- PUT {baseUrl}/v1/programs-participants/{id}

id: Identificador del program que desea actualizar

Se debe enviar en formato json los siguientes datos en el body:
```json
{
    "program_id": 9,
    "entity_id": 9,
    "entity_type": "challenge"
}
```

Se debe tener las mismas consideraciones para que en el POST

- DELETE {baseUrl}/v1/programs-participants/{id}

id: Identificador del program que desea eliminar


----------------------

## Nota:
Se debe tener en cuenta que todas las APIs anteriormente listadas requieren de la autentificación

### Ejemplo
```json
curl --location 'http://127.0.0.1:8000/api/v1/programs-participants?page=1' \
--header 'Authorization: Bearer 6|bSlXDWQxotPeg6RajjGDjXPqUGBMLFNrJH8Kxgzr82937a58'
```

## Pruebas unitarias

Para correr las pruebas unitarias basta con ejecutar el siguiente comando:
```
php artisan test
```

O si desea ejecutarlo por cada controlador puede hacerlo así:
```
php artisan test --filter ProgramsControllerTest
```
