## Descripción
Esta aplicación representa la estructura de un hipotético gestor de personas, donde únicamente se busca registrar su nombre, apellidos y edad, y con opción a listarlos filtrados por edad, y eliminar registros.

Es una aplicación creada únicamente como experimento y demostración de conocimientos.

## Requisitos
La aplicación está implementada en Laravel 11, por lo que se deberán cumplir con los <a href="https://laravel.com/docs/11.x/deployment#server-requirements">requisitos especificados por el propio framework</a>.

La aplicación ha sido desarrollada usando como motor de base de datos MySQL 8. No obstante, podría usarse como motor cualquiera de los soportados por Laravel.

Para la gestión de dependencias se usa <a href="https://getcomposer.org/">Composer</a> por lo que deberá encontrarse instalado previamente.

## Instalación
<ol>
    <li>Instalar todas las dependencias mediante Composer ejecutando en la carpeta raíz el siguiente comando: <pre>composer install</pre>
    (Suponiendo que Composer se encuentre instalado como comando).</li>
    <li>Crear el archivo de configuración del entorno, añadiendo un fichero con nombre ".env" en el directorio raíz. Puede usarse el incluido ".env.example" como plantilla.</li>
    <li>Elegir el motor de base de datos deseado de los soportados por Laravel, y configurar la conexión en el archivo .env como se especifica en la <a href="https://laravel.com/docs/11.x/database#introduction">documentación</a>.
    Si se elige como motor SQLite, se incluye un comando para crear cómodamente la base de datos, ejecutando: <pre>php artisan db:sqlite-generate</pre>
    </li>
    <li>Establecer la clave de cifrado ejecutando: <pre>php artisan key:generate</pre></li>
    <li>Crear la estructura de tablas de la base de datos ejecutando las migraciones: <pre>php artisan migrate</pre>
    Opcionalmente, si se desea partir con algunos datos de prueba, se puede aplicar el seeder:<pre>php artisan db:seed</pre>
    Este comando crea 10 personas con datos ficticios.</li>
    <li>Una vez instalada la aplicación, puede ponerse en funcionamiento con:
    <pre>php artisan serve</pre>
    Este comando crea un servicio web local que permite conectar con la aplicación mediante HTTP.
    </li>
</ol>


## Estructura
<u>Modelo de datos</u>

La aplicación solamente tiene una única tabla (people) para registrar los datos de las personas.
Las personas tienen un id único, nombre, apellidos, fecha de nacimiento, y fechas de registro y última modificación.

<u>Clases</u>
Dejando a un lado las clases incluidas por el propio framework, y algunas secundarias como las utilizadas para representar ciertos componentes web, podemos centrarnos en 3 grupos de clases fundamentalmente, ubicadas dentro del directorio app.

En primer lugar se encuentran los modelos (<strong>Models</strong>), que representan las entidades fundamentales y configuran sus propiedades y relaciones. Son clases estándar del <a href="https://laravel.com/docs/11.x/eloquent">ORM Eloquent</a>, por lo que mantienen todas sus características.

Los que se encargan de manipular los modelos para recuperar información o dar persistencia a los datos son los denominados "repositorios" (<strong>Repositories</strong>). Estos repositorios tienen los métodos principales para el CRUD (teniendo en cuenta que en este caso al ser un ejemplo algunos no se han incluido).

Por último, están los controladores (<strong>Controllers</strong>) dentro del directorio Http. Estos se encargan de gestionar las peticiones recibidas y generar las respuestas correspondientes apoyándose en los repositorios para obtener la información necesaria. En esta aplicación, únicamente se manejan peticiones tipo API en los controladores.


## Características funcionales
<u>Web</u>

La web consta únicamente de la página principal, que contiene el formulario para registrar personas y el listado.

Se accede directamente desde la ruta raíz (/).

<u>API</u>

La API Rest cuenta con las funciones necesarias para el registro, borrado y listado de personas.

Todas las funciones son llamadas mediante "fetch" desde la vista principal. No se ha incluido Swagger ni ningún otro tipo de documentación de la API debido a la limitación de tiempo, pero son funciones muy sencillas.

## Configuraciones especiales
Se ha añadido un parámetro de configuración especial que determina cuál es la edad mínima para que una persona sea considerada adulta. Por defecto está puesta como 18, pero puede sobrescribirse desde el .env con el parámetro APP_ADULTS_MIN_AGE