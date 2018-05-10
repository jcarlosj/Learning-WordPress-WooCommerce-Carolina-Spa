<?php
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );    # Elimina el precio haciendo uso del hook.
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 1 );        # Agrega el precio haciendo uso del hook y estableciendo una nueva prioridad.

    # Imprime subtitulo de producto con ACF (Advanced Custom Fields)
    function carolina_spa_agrega_subtitulo_producto() {
        global $post;
        $subtitulo = get_field( 'subtitulo', $post -> ID );
        echo "<p class=\"sub-titulo\">{$subtitulo}</p>";
    }
    add_filter( 'woocommerce_single_product_summary', 'carolina_spa_agrega_subtitulo_producto', 6 );

    # Muestra vídeo de producto con ACF (Advanced Custom Fields) en un TAB o pestaña
    function carolina_spa_agrega_video_producto_tab( $tabs ) {
        $tabs[ 'video' ] = array(
            'title'    => 'Video',
            'priority' => 25,
            'callback' => 'get_video_producto'
        );

        #echo '<pre>'; var_dump( $tabs ); echo '</pre>';
        return $tabs;
    }
    add_filter( 'woocommerce_product_tabs', 'carolina_spa_agrega_video_producto_tab', 11, 1 );

    # Video de Producto
    function get_video_producto() {
        echo 'Aquí desplegará el video de producto';
    }

    # Limita la cantidad de productos que se van a mostrar en la tienda por página
    function carolina_spa_productos_por_pagina( $productos ) {
        $productos = 16;
        return $productos;
    }
    add_filter( 'loop_shop_per_page', 'carolina_spa_productos_por_pagina', 20 );

    # Cantidad de columnas de productos por pagína (Tienda)
    function carolina_spa_columnas( $columnas ) {
        $columnas = 4;
        return $columnas;
    }
    add_action( 'loop_shop_columns', 'carolina_spa_columnas', 20 );

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
    # Agrega nueva sección en el 'Home Page'
    function carolina_spa_seccion_casa() {
        $imagen = get_woocommerce_term_meta( 19, 'thumbnail_id', true );
        $imagen_categoria = wp_get_attachment_image_src( $imagen, 'full' );
        $imagen_fondo = $imagen_categoria[ 0 ];
        $shortcode = do_shortcode( '[product_category columns="4" category="spa-en-casa"]' );        # Crea un

        echo "
            <div class=\"spa-casa\">
                <div class=\"imagen-categoria\">";

        # Valida si la categoría tiene imagen
        if( $imagen_categoria ) {
            echo "
                <div class=\"imagen-destacada\" style=\"background-image: url( '{$imagen_categoria[0]}' );\"></div>
                <h2 class=\"section-title\">Spa en Casa</h2>
            ";
        }

        echo "
                </div>
                    <div class=\"productos\">
                        {$shortcode}
                    </div>
            </div>";

        /* NOTA: 'do_shortcode' convierte una función en codigo en WordPress */
    }
    add_action( 'homepage', 'carolina_spa_seccion_casa', 30 );
    # Muestra 4 categorías en el 'Home Page'
    function carolina_spa_categorias( $args ) {
        $args[ 'limit' ] = 4;
        $args[ 'columns' ] = 4;
        return $args;
    }
    add_action( 'storefront_product_categories_args', 'carolina_spa_categorias', 100 );
    # Cambiar nombre del filtro de búsqueda
    function carolina_spa_ordenar( $filtro ) {
        //echo '<pre>'; var_dump( $filtro ); echo '</pre>';
        $filtro[ 'date' ] = __( 'Ordenar productos nuevos primero' );        # Reescribe nombre del filtro de búsqueda de la tienda
        return $filtro;
    }
    add_filter( 'woocommerce_catalog_orderby', 'carolina_spa_ordenar', 40 );
    # Remueve pestaña 'descripción' de producto
    /*function carolina_spa_remover_tabs( $tabs ) {
        # echo '<pre>'; var_dump( $tabs ); echo '</pre>';
        unset( $tabs[ 'description' ] );
        return $tabs;
    }
    add_filter( 'woocommerce_product_tabs', 'carolina_spa_remover_tabs', 11, 1 ); */

    /* Enqueue scripts. */
    function carolina_spa_scripts() {
        # Implementa script 'AddThis!'
        wp_register_script(
          'add-this',                                  # Nombre que toma la función registrada en el Core de Wordpress
          'http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5af1f9d29fc1daa4',  # Ruta del fichero en el directorio JS de la plantilla
          array(),                                      # Dependencias (ficheros que deseamos que se carguen antes, vacio por ahora)
          '1.0.0',                                      # Versión del Script
          true                                          # Indica que carguen en el Footer (al final del documento que contiene el tema)
        );
        wp_enqueue_script( 'add-this' );
    }
    add_action( 'wp_enqueue_scripts', 'carolina_spa_scripts' );

    # Mostrar AddThis!
    function carolina_spa_addthis_buttons() {
        ?>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_inline_share_toolbox"></div>
        <?php
    }
    add_action( 'woocommerce_after_add_to_cart_form', 'carolina_spa_addthis_buttons' );

/*
    function carolina_spa_script_addthis() {
        ?>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5af1f9d29fc1daa4"></script>
        <?php
    }
    add_action( 'wp_footer', 'carolina_spa_script_addthis' );*/

    # Muestra descuento en porcentaje o en dinero (Aplicando la regla del 100)
    function carolina_spa_mostrar_descuento( $precio, $producto ) {
        #echo '<pre>'; var_dump( $producto ); echo '</pre>';
        #echo '<pre>'; var_dump( 'Precio corriente: ', $producto -> regular_price ); echo '</pre>';
        #echo '<pre>'; var_dump( 'Precio descuento: ', $producto -> sale_price ); echo '</pre>';

        if( $producto -> sale_price ) {
            if( $producto -> get_regular_price() < 100 ) {
                $porcentaje = round( ( ( $producto -> regular_price - $producto -> sale_price ) / $producto -> regular_price ) * 100 );
                return $precio. sprintf( __( '<br /><span class="ahorro"> Ahorro: %s &#37;</span>', 'woocommerce' ), $porcentaje );
            }
            else {
                $valor = wc_price( $producto -> regular_price - $producto -> sale_price );    # 'wc_price' función de WooCommerce para dar formato al precio con un símbolo de moneda.
                return $precio. sprintf( __( '<span class="ahorro"> Ahorro: %s </span>', 'woocommerce' ), $valor );
            }
        }

        return $precio;
    }
    add_filter( 'woocommerce_get_price_html', 'carolina_spa_mostrar_descuento', 10, 2 );
    # Modifica título de las pestañas del producto
    function carolina_spa_modifica_encabezado_pestanias_producto( $tabs ) {
        #echo '<pre>'; var_dump( $tabs ); echo '</pre>';
        global $post;

        # Valida si existe la pestaña 'description'
        if( isset( $tabs[ 'description' ][ 'title' ] ) ) {
            $tabs[ 'description' ][ 'title' ] = $post -> post_title;            # Cambia el valor predeterminado por el título del post (Nombre del Producto)
        }
        return $tabs;
    }
    add_filter( 'woocommerce_product_tabs', 'carolina_spa_modifica_encabezado_pestanias_producto', 10, 1 );
    # Modifica encabezado de la descripción del producto
    function carolina_spa_modifica_encabezado_descripcion_producto( $tabs ) {
        #echo '<pre>'; var_dump( $tabs ); echo '</pre>';

        global $post;        # Obtiene la variable $post global actual

        return $post -> post_title;
    }
    add_filter( 'woocommerce_product_description_heading', 'carolina_spa_modifica_encabezado_descripcion_producto', 10, 1 );

    /*******************************************************************************
     * Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
     ******************************************************************************/
    // Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
    // Establecer un valor de tiempo de espera personalizado para cURL. Usar un valor alto para prioridad para asegurar que la función se ejecute después de cualquier otra agregada al mismo gancho de acción.
    add_action('http_api_curl', 'sar_custom_curl_timeout', 9999, 1);
    function sar_custom_curl_timeout( $handle ){
    	curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
    	curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
    }
    // Setting custom timeout for the HTTP request
    // Establecer el tiempo de espera personalizado para la solicitud HTTP
    add_filter( 'http_request_timeout', 'sar_custom_http_request_timeout', 9999 );
    function sar_custom_http_request_timeout( $timeout_value ) {
    	return 30; // 30 seconds. Too much for production, only for testing.
    }
    // Setting custom timeout in HTTP request args
    // Configurar el tiempo de espera personalizado en argumentos de solicitud HTTP
    add_filter('http_request_args', 'sar_custom_http_request_args', 9999, 1);
    function sar_custom_http_request_args( $r ){
    	$r['timeout'] = 30; // 30 seconds. Too much for production, only for testing.
    	return $r;
    }
?>
