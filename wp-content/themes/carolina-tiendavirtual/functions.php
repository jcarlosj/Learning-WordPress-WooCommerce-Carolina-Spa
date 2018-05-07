<?php
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );    # Elimina el precio haciendo uso del hook.
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 1 );        # Agrega el precio haciendo uso del hook y estableciendo una nueva prioridad.

    /* Limita la cantidad de productos que se van a mostrar en la tienda por página */
    function productos_por_pagina( $cantidad ) {
        $cantidad = 6;
        return $cantidad;
    }
    add_filter( 'loop_shop_per_page', 'productos_por_pagina', 20 );

?>
