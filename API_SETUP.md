# Cómo obtener tu API Key y Place ID de Google

Para que **Google Reviews Pro** funcione, necesitas dos cosas de Google:
1.  **API Key**: Una clave para que tu web pueda "hablar" con Google.
2.  **Place ID**: El identificador único de tu negocio en Google Maps.

Sigue estos pasos sencillos:

---

## 1. Obtener la API Key 🔑

1.  Ve a la [Google Cloud Console](https://console.cloud.google.com/).
2.  Si es tu primera vez, crea un **Nuevo Proyecto** (ponle un nombre como "Web Reseñas").
3.  Una vez en el proyecto, usa el buscador superior y busca **"Places API"**.
4.  Selecciona **Places API** (o "Places API (New)") y pulsa en **Habilitar**.
5.  Ahora ve al menú lateral: **Credenciales**.
6.  Haz clic en **+ CREAR CREDENCIALES** > **Clave de API**.
7.  ¡Listo! Copia esa clave larga (empieza por `AIza...`). Esa es tu `GRP_API_KEY`.

> **Recomendación de Seguridad:** En la configuración de la clave, bajo "Restricciones de API", selecciona "Restringir clave" y marca solo "Places API". Esto evita usos no autorizados.

---

## 2. Obtener el Place ID 📍

1.  Ve a la herramienta oficial: [Google Maps Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id).
2.  En el mapa que aparece, verás una barra de búsqueda en la parte superior.
3.  Escribe el nombre de tu negocio tal como aparece en Google Maps.
4.  Selecciónalo de la lista.
5.  Aparecerá un globo en el mapa con información. Busca donde dice:
    `Place ID: ChIJ...`
6.  Copia ese código largo de letras y números. Ese es tu `GRP_PLACE_ID`.

---

## 3. Configurar el Plugin

1.  Abre el archivo `google-reviews-pro.php` de este plugin.
2.  Pega tus credenciales en las líneas correspondientes:

```php
define( 'GRP_API_KEY', 'PEGA_TU_CLAVE_AQUI' ); 
define( 'GRP_PLACE_ID', 'PEGA_TU_PLACE_ID_AQUI' ); 
```

¡Y eso es todo! Tu plugin ya debería mostrar las reseñas.
