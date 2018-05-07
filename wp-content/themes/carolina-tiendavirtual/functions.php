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
?>
