# Google Reviews Pro - Free and Lightweight Plugin 🌟

**Google Reviews Pro** es un plugin gratuito y ligero para WordPress que te permite mostrar reseñas de Google Maps en tu sitio web utilizando la API oficial de Google Places. Diseñado con un enfoque "Mobile First" y optimizado para el rendimiento mediante un sistema de caché inteligente.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)
![License](https://img.shields.io/badge/license-GPLv2-green.svg)

## 🚀 Características

*   **Conexión API Oficial**: Utiliza la Google Places API para obtener datos fiables y actualizados.
*   **Sistema de Caché Inteligente**: Implementa WordPress Transients para guardar las reseñas durante 12 horas, minimizando las llamadas a la API y mejorando la velocidad de carga.
*   **Filtrado de Calidad**: Muestra automáticamente solo las reseñas más relevantes (4 y 5 estrellas).
*   **Diseño Moderno y Responsive**:
    *   📱 Móvil: 1 columna
    *   💻 Tablet: 2 columnas
    *   🖥️ Escritorio: 3 columnas
*   **Shortcode Flexible**: Fácil de implementar en cualquier página o post.
*   **Ligero**: Sin dependencias externas pesadas, CSS inyectado directamente para mayor portabilidad.

## 📋 Requisitos

*   WordPress 5.0 o superior.
*   PHP 7.4 o superior.
*   Una **API Key** de Google Maps Platform con la **Places API** habilitada.
*   El **Place ID** de tu negocio en Google Maps.

## 🛠️ Instalación

1.  Descarga este repositorio como un archivo `.zip`.
2.  En tu panel de WordPress, ve a **Plugins > Añadir nuevo > Subir plugin**.
3.  Sube el archivo `.zip` y haz clic en **Instalar ahora**.
4.  **IMPORTANTE**: Antes de activar (o justo después), debes configurar tus credenciales (ver sección Configuración).
5.  Activa el plugin.

## ⚙️ Configuración

Para que el plugin funcione, necesitas editar el archivo principal `google-reviews-pro.php` e introducir tus credenciales de Google.

1.  Abre el archivo `google-reviews-pro.php` con un editor de código o desde el Editor de Archivos de Plugins de WordPress.
2.  Busca la sección de configuración al principio del archivo:

```php
// =============================================================================
// CONFIGURACIÓN (EDITAR AQUÍ)
// =============================================================================
// Pega tu API Key de Google Maps Platform aquí
define( 'GRP_API_KEY', 'TU_API_KEY_AQUI' ); 

// Pega el Place ID de tu negocio aquí
define( 'GRP_PLACE_ID', 'TU_PLACE_ID_AQUI' ); 
```

3.  Reemplaza `'TU_API_KEY_AQUI'` con tu API Key real.
4.  Reemplaza `'TU_PLACE_ID_AQUI'` con el Place ID de tu negocio.
5.  Guarda los cambios.

## 💻 Uso

Utiliza el siguiente shortcode en cualquier entrada, página o widget de texto para mostrar las reseñas:

```shortcode
[google_reviews]
```

### Atributos Disponibles

*   `count`: Define el número de reseñas a mostrar. (Por defecto: 3).

**Ejemplo:** Mostrar 6 reseñas.

```shortcode
[google_reviews count="6"]
```

## 🎨 Personalización

El CSS está incluido dentro de la función `grp_render_reviews_html` en el archivo principal. Puedes modificar los estilos directamente allí para adaptarlos a la identidad visual de tu marca.

Las clases principales son:
*   `.grp-container`: Contenedor principal (Grid).
*   `.grp-card`: Tarjeta individual de reseña.
*   `.grp-stars`: Estrellas de calificación.
*   `.grp-author-name`: Nombre del autor.

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor, abre un *issue* o envía un *pull request* para mejoras y correcciones.

## 📄 Licencia

Este proyecto está bajo la licencia GPLv2.
