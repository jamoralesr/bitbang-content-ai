# Bitbang Content AI

Sistema de gestión y procesamiento de contenido con Inteligencia Artificial para Bitbang.

## Descripción

Esta aplicación Laravel está diseñada para gestionar y procesar contenido utilizando Inteligencia Artificial. Permite la importación de posts y categorías desde archivos CSV, y realiza procesamiento automático de contenido utilizando IA para generar resúmenes, categorizar contenido y asignar etiquetas.

## Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- MySQL/MariaDB
- Laravel 10.x

## Estructura de la Base de Datos

### Tablas Principales

1. **users**: Tabla estándar de Laravel para gestión de usuarios
2. **posts**: Almacena los artículos con los siguientes campos:
   - entry_id
   - url
   - title
   - resumen
   - texto_descriptivo
   - texto_descriptivo_sin_html
   - regional
   - temas (JSON)
   - categorias (JSON)
   - tags (JSON)
3. **categories**: Gestión de categorías
4. **jobs**: Cola de trabajos asíncronos
5. **cache**: Caché del sistema

## Comandos Personalizados

### Importación de Datos

1. `php artisan posts:import-from-csv`
   - Importa posts desde un archivo CSV

2. `php artisan categories:import-from-csv`
   - Importa categorías desde un archivo CSV

### Procesamiento con IA

1. `php artisan posts:process-contents-with-ia`
   - Procesa el contenido de los posts para generar resúmenes automáticos utilizando IA
   - Genera resúmenes periodísticos de 40-55 palabras

2. `php artisan posts:process-categories-with-ia`
   - Analiza el contenido de los posts para asignar categorías automáticamente

3. `php artisan posts:process-tags-with-ia`
   - Genera y asigna tags relevantes a los posts utilizando IA

## Modelos

### Post
- Gestiona los artículos y su contenido
- Incluye transformadores automáticos para campos JSON (temas, categorías, tags)
- Maneja la limpieza y procesamiento de datos

### Category
- Gestiona la taxonomía de categorías
- Permite la organización jerárquica del contenido

## Instalación

1. Clonar el repositorio
```bash
git clone [url-del-repositorio]
```

2. Instalar dependencias
```bash
composer install
```

3. Configurar el archivo .env
```bash
cp .env.example .env
```

4. Generar la clave de la aplicación
```bash
php artisan key:generate
```

5. Ejecutar las migraciones
```bash
php artisan migrate
```

## Uso

1. Para importar datos:
   - Preparar los archivos CSV con el formato requerido
   - Ejecutar los comandos de importación correspondientes

2. Para procesar contenido con IA:
   - Asegurarse de tener configuradas las credenciales de la IA
   - Ejecutar los comandos de procesamiento en el orden deseado
