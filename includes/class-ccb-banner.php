<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class CCB_Banner {

    public static function init(): void {
        add_action( 'wp_enqueue_scripts', [ self::class, 'enqueue' ] );
        add_action( 'wp_footer',          [ self::class, 'render' ] );
    }

    public static function enqueue(): void {
        $options = wp_parse_args( get_option( CCB_OPTIONS_KEY, [] ), CCB_Settings::defaults() );

        wp_enqueue_style(
            'ccb-front',
            CCB_PLUGIN_URL . 'assets/css/front.css',
            [],
            CCB_VERSION
        );

        wp_enqueue_script(
            'ccb-front',
            CCB_PLUGIN_URL . 'assets/js/front.js',
            [],
            CCB_VERSION,
            true
        );

        wp_localize_script( 'ccb-front', 'ccbConfig', [
            'cookieDays' => absint( $options['cookie_days'] ),
        ] );

        // Inject color tokens as inline CSS custom properties.
        $inline = sprintf(
            ':root{--ccb-bg:%s;--ccb-text:%s;--ccb-btn:%s;--ccb-btn-text:%s;}',
            esc_attr( $options['bg_color'] ),
            esc_attr( $options['text_color'] ),
            esc_attr( $options['btn_color'] ),
            esc_attr( $options['btn_text'] )
        );
        wp_add_inline_style( 'ccb-front', $inline );
    }

    public static function render(): void {
        $options = wp_parse_args( get_option( CCB_OPTIONS_KEY, [] ), CCB_Settings::defaults() );
        $privacy_link = '';
        if ( ! empty( $options['privacy_url'] ) ) {
            $privacy_link = sprintf(
                ' <a href="%s" class="ccb-privacy-link">%s</a>',
                esc_url( $options['privacy_url'] ),
                esc_html( $options['privacy_label'] )
            );
        }
        ?>
        <div id="ccb-banner" class="ccb-banner" role="region" aria-label="<?php esc_attr_e( 'Cookie consent', 'cookie-consent-banner' ); ?>" hidden>
            <p class="ccb-message">
                <?php echo esc_html( $options['message'] ); ?>
                <?php echo $privacy_link; // Already escaped above. ?>
            </p>
            <button id="ccb-accept" class="ccb-btn" type="button">
                <?php echo esc_html( $options['button_label'] ); ?>
            </button>
        </div>
        <?php
    }
}
