<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Bookly Custom Fields (Add-on)
Plugin URI: http://booking-wp-plugin.com
Description: Bookly Custom Fields add-on allows you to add custom fields to the booking form.
Version: 1.2
Author: Ladela Interactive
Author URI: http://booking-wp-plugin.com
Text Domain: bookly-custom-fields
Domain Path: /languages
License: Commercial
*/

if ( ! function_exists( 'bookly_custom_fields_loader' ) ) {
    include_once __DIR__ . '/autoload.php';

    if ( class_exists( '\Bookly\Lib\Plugin' ) && version_compare( Bookly\Lib\Plugin::getVersion(), '14.8', '>=' ) ) {
        BooklyCustomFields\Lib\Plugin::run();
    } else {
        add_action( 'init', function () {
            if ( current_user_can( 'activate_plugins' ) ) {
                add_action( 'admin_init', function () {
                    deactivate_plugins( 'bookly-addon-custom-fields/main.php', false, is_network_admin() );
                } );
                add_action( is_network_admin() ? 'network_admin_notices' : 'admin_notices', function () {
                    printf( '<div class="updated"><h3>Bookly Custom Fields (Add-on)</h3><p>The plugin has been <strong>deactivated</strong>.</p><p><strong>Bookly v%s</strong> is required.</p></div>',
                        '14.8'
                    );
                } );
                unset ( $_GET['activate'], $_GET['activate-multi'] );
            }
        } );
    }
}