<?php
/**
 * Plugin Name: wp-no-favicon
 * Description: WordPress既定の“W”ファビコンを非表示にする
 * Author: Computer Lib
 */

add_action(
    'init', 
    function () {
        // 1) WP に「サイトアイコンは無い」と思わせる
        add_filter( 'pre_option_site_icon', function () { return 0; }, 99 );

        // 2) コアの <head> 出力を止める（rel="icon", apple-touch-icon 等）
        remove_action( 'wp_head',    'wp_site_icon', 99 );
        remove_action( 'admin_head', 'wp_site_icon', 99 );
        remove_action( 'login_head', 'wp_site_icon', 99 );
        add_filter( 'site_icon_meta_tags', '__return_empty_array', 99 );

        // 3) テーマ/プラグインが get_site_icon_url() を使っても空にする保険
        add_filter( 'get_site_icon_url', function ($url) { return ''; }, 99 );

        // 4) /favicon.ico へ来たら 204 を返す（明示的に「無し」）
        add_action(
            'do_faviconico', 
            function () {
                status_header( 204 ); // No Content
                nocache_headers();
                exit;
            }, 
            0
        );
    }, 
    20
);
