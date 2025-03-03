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
    git checkout -b feature/report-view
    ```

2. Realizar Cambios y Subirlos al Repositorio

    - Después de completar el trabajo en tu rama, asegúrate de seguir estos pasos antes de abrir un pull request.

    #### Paso a Paso:

    - **Añadir los archivos que deseas confirmar al commit:**

    ```bash
    git add .
    ```

    - **Crear un commit con un mensaje descriptivo siguiendo el formato de conventional commits:**

    ```bash
    git commit -m "Commit que siga las convenciones previamente presentadas"
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
