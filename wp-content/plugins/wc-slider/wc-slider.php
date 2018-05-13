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

// No permitir que este se cargue directamente
defined( 'ABSPATH' ) or die( 'No Script kiddies please!' );

// Obtener un Path
define( 'WC_SLIDER_PATH', plugin_dir_url( __FILE__ ) );

// Cargar Scripts y StyleSheets
function wc_slider_scripts() {
    wp_enqueue_style( 'bxslider-css', WC_SLIDER_PATH. '/assets/css/jquery.bxslider.min.css', '', '4.2.1' );    # Agrega CSS de la librería BxSlider

    # Valida si el script de jQuery ya está registrado o cargado en la página
    if( wp_script_is( 'jquery', 'enqueued' ) ) {
        return;
    }
    else {
        wp_enqueue_script( 'jquery' );
    }
    
    wp_enqueue_script( 'bxslider-js', WC_SLIDER_PATH. '/assets/js/jquery.bxslider.min.js', '', '4.2.1'  );
}
add_action( 'wp_enqueue_scripts', 'wc_slider_scripts' );
