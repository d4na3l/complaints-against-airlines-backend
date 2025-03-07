# complaints-against-airlines-backend

## Instrucciones para Clonar el Repositorio y Convenciones de Colaboración

### Clonar el Repositorio

```bash
git clone https://github.com/d4na3l/complaints-against-airlines-backend
cd complaints-against-airlines-backend
```

### Convenciones de Colaboracion

Para colaborar en este proyecto, utilizaremos **conventional commits** como guía para mantener un historial de cambios claro y comprensible. Los siguientes tipos de commit están definidos para facilitar la organización y comprensión de las modificaciones:

-   **chore**: Modificaciones menores en la estructura o configuración.
    -   Ejemplo:
        -   `chore: añadir .gitignore para archivos temporales`
        -   `chore: actualizar dependencias en ...`
        -   `chore: reorganizar estructura de carpetas en /src`
-   **feat**: Agregar una nueva funcionalidad o script.
    -   Ejemplo:
        -   `feat: agregar script para análisis de ...`
        -   `feat: implementar función para procesar datos ...`
        -   `feat: añadir visualización de tendencias de ...`
-   **fix**: Corregir errores encontrados en el código o en la estructura.
    -   Ejemplo:
        -   `fix: corregir error en la carga de datos en ...`
        -   `fix: solucionar problema de compatibilidad en ...`
        -   `fix: ajustar visualización en gráficos de ...`
-   **docs**: Cambios en la documentación.
    -   Ejemplo:
        -   `docs: actualizar README con instrucciones para configurar el entorno`
        -   `docs: añadir explicación de variables en ...`
        -   `docs: corregir formato de ejemplo en documentación`
-   **refactor**: Cambios en el código que no alteran la funcionalidad pero mejoran la estructura.
    -   Ejemplo:
        -   `refactor: optimizar funciones de limpieza en ...`
        -   `refactor: simplificar lógica de análisis en ...`
        -   `refactor: reorganizar funciones auxiliares en ...`

## Flujo de trabajo con Git

1. Configuración de la Rama de Trabajo

    - Trabajaremos en la rama `develop` para probar y desarrollar nuevas funcionalidades. Para una organización adecuada, sigue estos pasos:

    #### Paso a Paso:
    - **Crear una nueva rama para tu trabajo:**

    ```bash
    git branch develop
    ```

    - **Cambiar a la rama `develop`:**

    ```bash
    git checkout develop
    ```

    - **Actualizar la rama develop con los últimos cambios del repositorio:**

    ```bash
    git pull origin develop
    ```

    - **Crear una rama nueva para la funcionalidad específica (basada en develop):** Usa un nombre descriptivo para la nueva rama. Por ejemplo, si trabajas en el análisis de empleabilidad:

    ```bash
    git branch feature/report-view
    ```

    ```bash
    git checkout feature/report-view
    ```

2. Realizar Cambios y Subirlos al Repositorio

    - Después de completar el trabajo en tu rama, asegúrate de seguir estos pasos antes de abrir un pull request.

    #### Paso a Paso:

    - **Añadir los archivos que deseas confirmar al commit:**
    
        - Ejemplo 1

           ```bash
           git add .
           ```

       - Ejemplo 2
   
           ```bash
           git add name_file1.php name_file2.php directory/another_directory
           ```

    - **Crear un commit con un mensaje descriptivo siguiendo el formato de conventional commits:**

    ```bash
    git commit -m "commit: seguir las convenciones previamente presentadas"
    ```

    - **Enviar la rama con tus cambios al repositorio:**

    ```bash
    git push origin feature/report-view
    ```

3. Abrir un Pull Request para Revisión de Cambios

    - Una vez que los cambios estén en tu rama en GitHub, abre un pull request hacia la rama develop para revisión.

    #### Paso a Paso:

    - **Acceder al repositorio en GitHub.**
    - **Seleccionar la pestaña `Pull requests`.**
    - **Hacer clic en `New pull request`.**
    - **Seleccionar `develop` como rama de destino y tu rama `(feature/report-view)` como rama de origen.**
    - **Escribir una descripción detallada del pull request explicando los cambios realizados.**
    - **Solicitar revisión para que el administrador pueda revisar y dar feedback.**

    **_NOTA:_** No fusionar el pull request a develop directamente. Solo el administrador tiene permisos para fusionar los cambios después de la revisión.

## Ejecucion de la aplicacion

1. Creacion de Base de Datos de PostgreSQL
   
    ### Windows
      
    #### Paso a Paso:
    - **Descargar el instalador:**
      [Link al Instalador de PostgreSQL](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads)

      **__NOTA:_** La version usada de PostgreSQL es la 15.10

    - **Ejecutar el instalador y seguir las instrucciones:**
        [Seguir las instrucciones](https://www.enterprisedb.com/docs/supported-open-source/postgresql/installing/windows/)

      > [!IMPORTANT]
      > 
      > Si alguna versión de PostgreSQL ha sido instalada previamente, el puerto por defecto se mapeará al 5433.

    - **Probamos la instalación:**
      ```cmd
      psql -U postgres
      ```

    - **Creamos un usuario en la base de datos:** 
      ```psql
      CREATE USER nuevo_usuario WITH PASSWORD 'contraseña_usuario';
      ```
      
    - **Le damos superusuario al usuario creado:**
      ```psql
      ALTER USER nuevo_usuario WITH SUPERUSER;
      ```

    - **Creamos la base de datos:**
      ```psql
      CREATE DATABASE nombre_basededatos OWNER nuevo_usuario;
      ```

    - **Garantizamos que nuestro usuario tenga todos los privilegios sobre la base de datos creada:**
      ```psql
      GRANT ALL PRIVILEGES ON DATABASE nombre_basededatos TO nuevo_usuario;
      ```

    ### Linux
   
    #### Paso a Paso:
    - **Actualizamos repositorios:**
      ```bash
      sudo apt update
      ```

    - **Instalamos PostgreSQL:**
      ```bash
      sudo apt install postgresql
      ```

    - **Cambiamos al usuario de postgres:**
      ```bash
      sudo -i -u postgres
      ```
      
    - **Inicializamos la consola postgres:**
      ```bash
      psql
      ```
      
    - **Cambiamos la contraseña por defecto del usuario PostgreSQL:**
      ```bash
      ALTER USER postgres WITH PASSWORD 'tu_nueva_contraseña';
      ```

    - **Creamos un usuario en la base de datos:** 
      ```psql
      CREATE USER nuevo_usuario WITH PASSWORD 'contraseña_usuario';
      ```
      
    - **Le damos superusuario al usuario creado:**
      ```psql
      ALTER USER nuevo_usuario WITH SUPERUSER;
      ```

    - **Creamos la base de datos:**
      ```psql
      CREATE DATABASE nombre_basededatos OWNER nuevo_usuario;
      ```

    - **Garantizamos que nuestro usuario tenga todos los privilegios sobre la base de datos creada:**
      ```psql
      GRANT ALL PRIVILEGES ON DATABASE nombre_basededatos TO nuevo_usuario;
      ```

3. Configuración de las variables de Entorno:
   
   #### Paso a Paso:
    - **Copiar el contenido del archivo .env.example y pegarlo en un nuevo archivo llamado .env**
  
    - **Cambiar valores de las variables:**
      ```code
      DB_CONNECTION=pgsql
      DB_HOST=localhost
      DB_PORT=5432 // o 5433 dependiendo de tu caso
      DB_DATABASE=nombre_delabasededatos
      DB_PATH=public // si la base de datos fue creada en el esquema public
      DB_USERNAME=nombre_deusuario
      DB_PASSWORD=contraseña_usuario
      ```

4. Instalación de dependencias
   
   #### Paso a Paso:
   - **Instalar las dependencias del proyecto:**
       ```bash
         composer install
       ```
        >[!NOTE]
        >
        > Asegurese que todas las dependencias esten instaladas en su entorno de desarrollo (composer, php, ...)

    - **Generar un key en dado caso que no se haya generado automaticamente:**
        ```bash
        php artisan key:generate
        ```

5. Migraciones y ejecución de la aplicación

    - **Generar las migraciones a la base de datos:**
      ```bash
      php artisan migrate:refresh --seed
      ```

    - **Ejecutar sevidor de Laravel:**
      ```bash
      php artisan serve
      ```
