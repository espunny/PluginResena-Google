<?php
/**
 * Plugin Name: Google Reviews Pro
 * Description: Muestra reseñas de Google Maps mediante la API oficial con diseño moderno y sistema de caché.
 * Version: 1.0.0
 * Author: Rubén García
 * Text Domain: google-reviews-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// =============================================================================
// CONFIGURACIÓN (EDITAR AQUÍ)
// =============================================================================
// Pega tu API Key de Google Maps Platform aquí
define( 'GRP_API_KEY', 'TU_API_KEY_AQUI' ); 

// Pega el Place ID de tu negocio aquí
define( 'GRP_PLACE_ID', 'TU_PLACE_ID_AQUI' ); 
// =============================================================================

/**
 * Shortcode principal: [google_reviews]
 *
 * Atributos:
 * - count: Número de reseñas a mostrar (defecto: 3)
 */
function grp_shortcode_handler( $atts ) {
    $atts = shortcode_atts( array(
        'count' => 3,
    ), $atts, 'google_reviews' );

    // 1. Intentar obtener reseñas del caché (Transient)
    $cached_reviews = get_transient( 'grp_google_reviews' );

    if ( false === $cached_reviews ) {
        // 2. Si no hay caché, llamar a la API
        $reviews_data = grp_fetch_reviews_from_api();

        if ( is_wp_error( $reviews_data ) ) {
            return '<p style="color:red;">Error al obtener reseñas: ' . esc_html( $reviews_data->get_error_message() ) . '</p>';
        }

        if ( empty( $reviews_data ) ) {
            return '<p>No se encontraron reseñas.</p>';
        }

        // 3. Filtrar y Ordenar
        // Filtrar: Solo 4 o 5 estrellas
        $filtered_reviews = array_filter( $reviews_data, function( $review ) {
            return isset( $review['rating'] ) && intval( $review['rating'] ) >= 4;
        } );

        // Ordenar: Por fecha (time) descendente (más reciente primero)
        usort( $filtered_reviews, function( $a, $b ) {
            return $b['time'] - $a['time'];
        } );

        // Guardar en caché por 12 horas
        set_transient( 'grp_google_reviews', $filtered_reviews, 12 * HOUR_IN_SECONDS );
        
        $cached_reviews = $filtered_reviews;
    }

    // 4. Limitar cantidad
    $display_reviews = array_slice( $cached_reviews, 0, intval( $atts['count'] ) );

    // 5. Renderizar HTML
    ob_start();
    grp_render_reviews_html( $display_reviews );
    return ob_get_clean();
}
add_shortcode( 'google_reviews', 'grp_shortcode_handler' );

/**
 * Función para conectar con Google Places API
 */
function grp_fetch_reviews_from_api() {
    if ( empty( GRP_API_KEY ) || empty( GRP_PLACE_ID ) || GRP_API_KEY === 'TU_API_KEY_AQUI' || GRP_PLACE_ID === 'TU_PLACE_ID_AQUI' ) {
        return new WP_Error( 'missing_config', 'Faltan configurar la API Key o el Place ID en el plugin.' );
    }

    $url = add_query_arg( array(
        'place_id' => GRP_PLACE_ID,
        'fields'   => 'reviews', // Solo pedimos las reseñas para ahorrar
        'key'      => GRP_API_KEY,
        'language' => 'es', // Idioma español
    ), 'https://maps.googleapis.com/maps/api/place/details/json' );

    $response = wp_remote_get( $url );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( isset( $data['status'] ) && $data['status'] !== 'OK' ) {
        return new WP_Error( 'api_error', 'Error de API: ' . $data['status'] . ( isset( $data['error_message'] ) ? ' - ' . $data['error_message'] : '' ) );
    }

    if ( isset( $data['result']['reviews'] ) ) {
        return $data['result']['reviews'];
    }

    return array();
}

/**
 * Función para renderizar el HTML y CSS
 */
function grp_render_reviews_html( $reviews ) {
    // CSS Inyectado
    ?>
    <style>
        .grp-container {
            display: grid;
            grid-template-columns: 1fr; /* Móvil: 1 columna */
            gap: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Sans-serif limpia */
            margin: 20px 0;
        }

        /* Tablet: 2 columnas */
        @media (min-width: 768px) {
            .grp-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Escritorio: 3 columnas */
        @media (min-width: 1024px) {
            .grp-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .grp-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); /* Sombra suave */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .grp-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .grp-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .grp-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%; /* Circular */
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #f0f0f0;
        }

        .grp-author-info {
            display: flex;
            flex-direction: column;
        }

        .grp-author-name {
            font-weight: bold;
            font-size: 1.1em;
            color: #333;
            margin: 0;
        }

        .grp-meta {
            font-size: 0.85em;
            color: #777;
        }

        .grp-stars {
            color: #FFD700; /* Amarillo/Dorado */
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .grp-text {
            font-size: 0.95em;
            color: #555;
            line-height: 1.5;
            flex-grow: 1; /* Para que las tarjetas tengan altura igual */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 5; /* Truncar después de 5 líneas */
            -webkit-box-orient: vertical;
        }
    </style>

    <div class="grp-container">
        <?php foreach ( $reviews as $review ) : ?>
            <div class="grp-card">
                <div class="grp-header">
                    <img src="<?php echo esc_url( $review['profile_photo_url'] ); ?>" alt="<?php echo esc_attr( $review['author_name'] ); ?>" class="grp-avatar">
                    <div class="grp-author-info">
                        <h4 class="grp-author-name"><?php echo esc_html( $review['author_name'] ); ?></h4>
                        <span class="grp-meta"><?php echo esc_html( $review['relative_time_description'] ); ?></span>
                    </div>
                </div>
                
                <div class="grp-stars">
                    <?php 
                    $rating = intval( $review['rating'] );
                    for ( $i = 0; $i < 5; $i++ ) {
                        echo ( $i < $rating ) ? '★' : '☆';
                    }
                    ?>
                </div>

                <div class="grp-text">
                    <?php echo wp_kses_post( $review['text'] ); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
