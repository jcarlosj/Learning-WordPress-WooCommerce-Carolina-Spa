<?php
/*
    Plugin Name: BxSlider para WordPress
    Plugin URI:
    Description: Agrega BxSlider a WooCommerce (Personalizado)
    Version: 1.0.0
    Author: Juan Carlos Jiménez Gutiérrez
    Author URI:
    Text Domain:
    Domain Path:
*/

# No permitir que este se cargue directamente
defined( 'ABSPATH' ) or die( 'No Script kiddies please!' );

# Obtener un Path
define( 'WC_SLIDER_PATH', plugin_dir_url( __FILE__ ) );

# Cargar Scripts y StyleSheets
function wc_slider_scripts() {
    wp_enqueue_style( 'bxslider-css', WC_SLIDER_PATH. 'assets/css/jquery.bxslider.min.css', '', '4.2.1' );    # Agrega CSS de la librería BxSlider

    # Valida el script de 'jQuery' ya está registrado o cargado en la página
    if( wp_script_is( 'jquery', 'enqueued' ) ) {
        return;
    }
    else {
        wp_enqueue_script( 'jquery' );
    }

    wp_enqueue_script( 'bxslider-js', WC_SLIDER_PATH. 'assets/js/jquery.bxslider.min.js', array( 'jquery' ), '4.2.1', true );
}
add_action( 'wp_enqueue_scripts', 'wc_slider_scripts' );

# Crear 'ShortCode' para mostrar productos
# Uso: [wc-slider]
function wc_slider_shortcode() {
    $args = array(
        'posts_per_page' => 10,
        'post_type' => 'product'
    );

    $slider_productos = new WP_Query( $args );
    echo '<ul class="slider">';

    while( $slider_productos -> have_posts() ): $slider_productos -> the_post();
        ?>
            <li>
                <a href="<?php the_permalink(); ?>">
                <?php
                    if( has_post_thumbnail( $slider_productos -> ID ) ) {
                        the_post_thumbnail( 'shop_catalog' );
                        /* Tamaños por defecto de imagenes para WooCommerce
                           shop_thumbnail (100x100 hard cropped),
                           shop_catalog (300x300 hard cropped),
                           shop_single (600x600 hard cropped) */
                    }
                    else {
                        $url_image = get_stylesheet_directory_uri(). '/assets/images/no-image.png';
                        echo "<img src=\"$url_image\" width=\"300\" height=\"300\" />";
                    }
                    the_title( '<h2>', '</h2>' );
                ?>
                </a>
            </li>
        <?php
    endwhile; wp_reset_postdata();
    echo '</ul>';

}
add_shortcode( 'wc-slider', 'wc_slider_shortcode' );

# Implementa el script que despliega la funcionalidad del Slider con la librería BxSlider de jQuery
function wc_slider_execute_script() { ?>
    <script type="text/javascript">
        $ = jQuery .noConflict();
        $( document ) .ready( function() {
            $( '.slider' ) .bxSlider({
                auto: true,
                minSlides: 4,
                maxSlides: 4,
                slideWidth: 250,
                slideMargin: 10,
                moveSlides: 1
            });
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'wc_slider_execute_script' );
