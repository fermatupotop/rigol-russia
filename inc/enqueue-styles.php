<?php
/**
 * Подключение стилей для страниц
 *
 * @package Bardnwn_Child
 */

if (!defined('ABSPATH')) exit;

/**
 * Условная загрузка CSS для разных страниц
 */
function rigol_enqueue_page_styles() {
    // Базовые стили
    wp_enqueue_style(
        'bardnwn-base',
        get_stylesheet_directory_uri() . '/assets/css/base.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/base.css')
    );

    // Массив страниц и их стилей
    $page_styles = [
        'about' => 'pages/about.css',
        'contacts' => 'pages/contacts.css',
        'edo' => 'pages/edo.css',
        'sample-page' => 'pages/sample-page.css',
        'sale-rigol' => 'pages/sale-rigol.css',
        'contact' => 'pages/contact.css',
        'checkout' => 'pages/checkout.css',
		'news-rigol' => 'pages/news-rigol.css',
		'warranty' => 'pages/warranty.css',
		'faq' => 'pages/faq.css',
		'user-agreement' => 'pages/user-agreement.css',
		'privacy' => 'pages/privacy.css',
		'how-to-order' => 'pages/how-to-order.css',
		'proverka-garantii-rigol' => 'pages/proverka-garantii-rigol.css',
		'documents' => 'pages/documents.css'
    ];

    // Проверяем текущую страницу и подключаем нужный стиль
    foreach ($page_styles as $page_slug => $css_file) {
        if (is_page($page_slug) || ($page_slug === 'sample-page' && is_front_page())) {
            wp_enqueue_style(
                'bardnwn-' . $page_slug,
                get_stylesheet_directory_uri() . '/assets/css/' . $css_file,
                array('bardnwn-base'),
                filemtime(get_stylesheet_directory() . '/assets/css/' . $css_file)
            );
        }
    }
	
	// стили для новостей
	if(is_single()){
		wp_enqueue_style(
		'bardnwn-single',
		get_stylesheet_directory_uri() . '/assets/css/single/single.css',
		array('bardnwn-base'),
		filemtime(get_stylesheet_directory() .'/assets/css/single/single.css')
		);
	}

//стили для товаров
if(is_product()){
wp_enqueue_style(
'bardnwn-product',
get_stylesheet_directory_uri() . '/assets/css/product/product.css',
array('bardnwn-base'),
filemtime(get_stylesheet_directory() .'/assets/css/product/product.css')
);
}

// Стили для категорий товаров WooCommerce (на страницах категорий И на страницах товаров)
if ( is_product() || is_product_category() ) {
    
    $categories_to_load = [];

    // 1. Если мы на странице архива категории
    if ( is_product_category() ) {
        $category = get_queried_object();
        $categories_to_load[] = $category->slug;
    }
    
    // 2. Если мы на странице товара, получаем все его категории
    if ( is_product() ) {
        global $post;
        $product_cats = get_the_terms( $post->ID, 'product_cat' );
        
        if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) {
            foreach ( $product_cats as $cat ) {
                $categories_to_load[] = $cat->slug;
            }
        }
    }

    // Подключаем стили для найденных категорий
    foreach ( $categories_to_load as $category_slug ) {
        $category_css_path = get_stylesheet_directory() . '/assets/css/product-categories/' . $category_slug . '.css';
        $category_css_uri = get_stylesheet_directory_uri() . '/assets/css/product-categories/' . $category_slug . '.css';

        if ( file_exists( $category_css_path ) ) {
            wp_enqueue_style(
                'bardnwn-product-cat-' . $category_slug,
                $category_css_uri,
                array('bardnwn-base'),
                filemtime( $category_css_path )
            );
        }
    }
}
}
add_action('wp_enqueue_scripts', 'rigol_enqueue_page_styles');

// is_home() срабатывает, если страница назначена как "Страница записей" в настройках чтения
add_action('wp_enqueue_scripts', 'add_news_page_styles');
function add_news_page_styles() {
    if ( is_home() ) {
        wp_enqueue_style(
            'news-rigol-custom',
            get_stylesheet_directory_uri() . '/assets/css/pages/news-rigol.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/css/pages/news-rigol.css')
        );
    }
}
