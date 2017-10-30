<?php

namespace Clubdeuce\WPLib\Components;

/**
 * Class Customizer
 * @package Clubdeuce\WPLib\Components
 */
class Customizer extends \WPLib_Module_Base {

    const INSTANCE_CLASS = '\Clubdeuce\WPLib\Components\Customizer\Settings';

    /**
     * @var array
     */
    protected static $_controls = array();

    /**
     * @var array
     */
    protected static $_sections = array();

    /**
     * @var array
     */
    protected static $_settings = array();

    /**
     * @var array
     */
    protected static $_scripts = array();

    static function on_load() {

        self::add_class_action( 'customize_register' );
        self::add_class_action( 'customize_preview_init' );

    }

    /**
     * @param string $id
     * @param array  $section
     */
    static function register_section( $id, $section = array() ) {

        static::$_sections[ $id ] = $section;

    }

    /**
     * @param string $id
     * @param array  $setting
     */
    static function register_setting( $id, $setting = array() ) {

        static::$_settings[ $id ] = $setting;

    }

    /**
     * @param string $id
     * @param array  $args
     */
    static function register_control( $id, $args = array() ) {

        $key = $id;

        /**
         * Add support for passing a WP_Customize_Control object as the ID
         */
        if ( is_a( $id, '\WP_Customize_Control' ) ) {
            $key  = md5( serialize( $id ) );
            $args = $id;
        }

        static::$_controls[ $key ] = $args;

    }

    /**
     * @return array
     */
    static function settings() {

        return self::$_settings;

    }

    /**
     * @param \WP_Customize_Manager $wp_customize
     */
    static function _customize_register( $wp_customize ) {

        array_walk( static::$_sections, array( __CLASS__, '_add_section' ), $wp_customize );
        array_walk( static::$_settings, array( __CLASS__, '_add_setting' ), $wp_customize );
        array_walk( static::$_controls, array( __CLASS__, '_add_control'),  $wp_customize );

    }

    static function _customize_preview_init() {

        array_walk( static::$_scripts,  array( __CLASS__, '_enqueue_script' ) );

    }

    /**
     * @param array $script
     */
    protected static function _enqueue_script( $script ) {

        wp_enqueue_script( $script );

    }

    /**
     * @param array                 $section
     * @param string                $id
     * @param \WP_Customize_Manager $wp_customize
     * https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section
     */
    protected static function _add_section( $section = array(), $id, $wp_customize ) {

        $section = wp_parse_args( $section, array(
            'title'           => sprintf( __( 'Please specify a title for this section: %1$s', 'customizer' ), $id ),
            'priority'        => 160,
            'description'     => '',
            'active_callback' => '',
        ) );

        $wp_customize->add_section( $id, $section );

    }

    /**
     * @param array                 $setting
     * @param string                $id
     * @param \WP_Customize_Manager $wp_customize
     * https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
     */
    protected static function _add_setting( $setting = array(), $id, $wp_customize ) {

        $setting = wp_parse_args( $setting, array(
            'default'              => '',
            'type'                 => 'theme_mod',
            'capability'           => 'edit_theme_options',
            'theme_supports'       => null,
            'transport'            => 'refresh',
            'sanitize_callback'    => null,
            'sanitize_js_callback' => null,
        ) );

        $wp_customize->add_setting( $id, $setting );

    }

    /**
     * @param array                        $control
     * @param string|\WP_Customize_Control $id
     * @param \WP_Customize_Manager        $wp_customize
     * @link  https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
     */
    protected static function _add_control( $control = array(), $id, $wp_customize ) {

        if ( is_a( $control, '\WP_Customize_Control' ) ) {
            $id = $control;
            $control = null;
        } else {
            $control = wp_parse_args( $control, array(
                'label' => sprintf( __( 'Please specify a label for this control: %1$s', 'customizer' ), $id ),
                'description' => '',
                'section'     => null,
                'priority'    => 100,
                'type'        => 'text',
                'settings'    => null,
            ) );
        }

        $wp_customize->add_control( $id, $control );
    }

}
Customizer::on_load();