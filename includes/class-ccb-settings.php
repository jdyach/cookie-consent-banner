<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class CCB_Settings {

    public static function init(): void {
        add_action( 'admin_menu', [ self::class, 'add_page' ] );
        add_action( 'admin_init', [ self::class, 'register_settings' ] );
    }

    public static function add_page(): void {
        add_options_page(
            __( 'Cookie Consent Banner', 'cookie-consent-banner' ),
            __( 'Cookie Consent', 'cookie-consent-banner' ),
            'manage_options',
            'cookie-consent-banner',
            [ self::class, 'render_page' ]
        );
    }

    public static function register_settings(): void {
        register_setting(
            'ccb_settings_group',
            CCB_OPTIONS_KEY,
            [ 'sanitize_callback' => [ self::class, 'sanitize' ] ]
        );

        // Content section
        add_settings_section( 'ccb_content', __( 'Banner Content', 'cookie-consent-banner' ), '__return_null', 'ccb_settings_group' );

        $content_fields = [
            'message'       => __( 'Banner Message', 'cookie-consent-banner' ),
            'button_label'  => __( 'Accept Button Label', 'cookie-consent-banner' ),
            'privacy_url'   => __( 'Privacy Policy URL', 'cookie-consent-banner' ),
            'privacy_label' => __( 'Privacy Policy Link Text', 'cookie-consent-banner' ),
            'cookie_days'   => __( 'Consent Cookie Duration (days)', 'cookie-consent-banner' ),
        ];
        foreach ( $content_fields as $id => $label ) {
            add_settings_field( 'ccb_' . $id, $label, [ self::class, 'render_field' ], 'ccb_settings_group', 'ccb_content', [ 'field' => $id ] );
        }

        // Colors section
        add_settings_section( 'ccb_colors', __( 'Colors', 'cookie-consent-banner' ), '__return_null', 'ccb_settings_group' );

        $color_fields = [
            'bg_color'   => __( 'Banner Background', 'cookie-consent-banner' ),
            'text_color' => __( 'Banner Text', 'cookie-consent-banner' ),
            'btn_color'  => __( 'Button Background', 'cookie-consent-banner' ),
            'btn_text'   => __( 'Button Text', 'cookie-consent-banner' ),
        ];
        foreach ( $color_fields as $id => $label ) {
            add_settings_field( 'ccb_' . $id, $label, [ self::class, 'render_field' ], 'ccb_settings_group', 'ccb_colors', [ 'field' => $id ] );
        }
    }

    public static function render_field( array $args ): void {
        $options = wp_parse_args( get_option( CCB_OPTIONS_KEY, [] ), self::defaults() );
        $field   = $args['field'];
        $value   = $options[ $field ] ?? '';
        $name    = CCB_OPTIONS_KEY . '[' . esc_attr( $field ) . ']';

        switch ( $field ) {
            case 'message':
                printf( '<textarea name="%s" rows="3" class="large-text">%s</textarea>', esc_attr( $name ), esc_textarea( $value ) );
                break;
            case 'privacy_url':
                printf( '<input type="url" name="%s" value="%s" class="regular-text">', esc_attr( $name ), esc_attr( $value ) );
                break;
            case 'cookie_days':
                printf( '<input type="number" name="%s" value="%s" min="1" max="3650" class="small-text"> %s',
                    esc_attr( $name ), esc_attr( $value ), esc_html__( 'days', 'cookie-consent-banner' ) );
                break;
            case 'bg_color':
            case 'text_color':
            case 'btn_color':
            case 'btn_text':
                printf( '<input type="color" name="%s" value="%s">', esc_attr( $name ), esc_attr( $value ) );
                break;
            default:
                printf( '<input type="text" name="%s" value="%s" class="regular-text">', esc_attr( $name ), esc_attr( $value ) );
        }
    }

    public static function sanitize( mixed $input ): array {
        $d = self::defaults();
        return [
            'message'       => isset( $input['message'] )       ? sanitize_textarea_field( $input['message'] )       : $d['message'],
            'button_label'  => isset( $input['button_label'] )  ? sanitize_text_field( $input['button_label'] )      : $d['button_label'],
            'privacy_url'   => isset( $input['privacy_url'] )   ? esc_url_raw( $input['privacy_url'] )               : $d['privacy_url'],
            'privacy_label' => isset( $input['privacy_label'] ) ? sanitize_text_field( $input['privacy_label'] )     : $d['privacy_label'],
            'bg_color'      => isset( $input['bg_color'] )      ? sanitize_hex_color( $input['bg_color'] )  ?? $d['bg_color']   : $d['bg_color'],
            'text_color'    => isset( $input['text_color'] )    ? sanitize_hex_color( $input['text_color'] ) ?? $d['text_color'] : $d['text_color'],
            'btn_color'     => isset( $input['btn_color'] )     ? sanitize_hex_color( $input['btn_color'] )  ?? $d['btn_color']  : $d['btn_color'],
            'btn_text'      => isset( $input['btn_text'] )      ? sanitize_hex_color( $input['btn_text'] )   ?? $d['btn_text']   : $d['btn_text'],
            'cookie_days'   => isset( $input['cookie_days'] )   ? absint( $input['cookie_days'] )                    : $d['cookie_days'],
        ];
    }

    public static function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'ccb_settings_group' );
                do_settings_sections( 'ccb_settings_group' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function defaults(): array {
        return [
            'message'       => 'This website uses cookies to ensure you get the best experience.',
            'button_label'  => 'Got it',
            'privacy_url'   => '',
            'privacy_label' => 'Privacy Policy',
            'bg_color'      => '#1a1a1a',
            'text_color'    => '#ffffff',
            'btn_color'     => '#ffffff',
            'btn_text'      => '#1a1a1a',
            'cookie_days'   => 365,
        ];
    }
}
