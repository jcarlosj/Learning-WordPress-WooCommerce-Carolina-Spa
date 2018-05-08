<?php
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );    # Elimina el precio haciendo uso del hook.
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 1 );        # Agrega el precio haciendo uso del hook y estableciendo una nueva prioridad.

    /* Limita la cantidad de productos que se van a mostrar en la tienda por página */
    function productos_por_pagina( $cantidad ) {
        $cantidad = 6;
        return $cantidad;
    }
    add_filter( 'loop_shop_per_page', 'productos_por_pagina', 20 );

    # Cambia simbolo por código de divisa (abreviatura tres caracteres) correspondiente a moneda establecida en los ajustes del sitio, por ejemplo: pesos colombianos (COP)
    function carolina_spa_moneda( $simbolo, $moneda ) {
        $simbolo = "{$moneda } $";
        return $simbolo;
    }
    add_filter( 'woocommerce_currency_symbol', 'carolina_spa_moneda', 5, 2 );    # Hook, <nombre-funcion>, prioridad, número decimales

    # Modifica los créditos de la página
    function carolina_spa_creditos() {
        remove_action( 'storefront_footer', 'storefront_credit', 20 );           # Elimina los créditos por defecto de la plantilla
        add_action( 'storefront_after_footer', 'carolina_spa_copyright', 20 );   # Agrega derechos personalizados de la página
    }
    add_action( 'init', 'carolina_spa_creditos' );

    # Crea CopyRight Personalizado
    function carolina_spa_copyright() {
        echo '<div class="copyright">Derechos reservados &copy; ' .get_bloginfo( "name" ). ' ' .get_the_date( "Y" ). '</div>';
    }
    # Agrega banner de descuento (imagen) al 'Home Page'
    function carolina_spa_banner_descuento() {
        echo '<div class="banner-descuento"><img src="' .get_stylesheet_directory_uri(). '/assets/images/cupon.jpg" /></div>';
    }
    add_action( 'homepage', 'carolina_spa_banner_descuento', 15 );
    # Muestra 4 categorías en el 'Home Page'
    function carolina_spa_categorias( $args ) {
        $args[ 'limit' ] = 4;
        $args[ 'columns' ] = 4;
        return $args;
    }
    add_action( 'storefront_product_categories_args', 'carolina_spa_categorias', 100 );
?>
