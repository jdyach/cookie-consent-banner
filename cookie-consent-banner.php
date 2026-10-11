<?php
/**
 * Plugin Name:       Cookie Consent Banner
 * Plugin URI:        https://github.com/jdyach/cookie-consent-banner
 * Description:       Lightweight GDPR cookie consent notice. Displays a dismissible banner with configurable message, button label, and colors. Consent is stored in a cookie — no external scripts or tracking.
 * Version:           1.0.0
 * Author:            Jacqui Dyachenko
 * Author URI:        https://jacquid.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cookie-consent-banner
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CCB_VERSION',    '1.0.0' );
define( 'CCB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CCB_OPTIONS_KEY', 'ccb_settings' );

require_once CCB_PLUGIN_DIR . 'includes/class-ccb-settings.php';
require_once CCB_PLUGIN_DIR . 'includes/class-ccb-banner.php';

function ccb_init(): void {
    CCB_Settings::init();
    CCB_Banner::init();
}
add_action( 'plugins_loaded', 'ccb_init' );

register_activation_hook( __FILE__, function (): void {
    if ( ! get_option( CCB_OPTIONS_KEY ) ) {
        add_option( CCB_OPTIONS_KEY, [
            'message'       => 'This website uses cookies to ensure you get the best experience.',
            'button_label'  => 'Got it',
            'privacy_url'   => '',
            'privacy_label' => 'Privacy Policy',
            'bg_color'      => '#1a1a1a',
            'text_color'    => '#ffffff',
            'btn_color'     => '#ffffff',
            'btn_text'      => '#1a1a1a',
            'cookie_days'   => 365,
        ] );
    }
} );
