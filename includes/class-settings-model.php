<?php

namespace Clubdeuce\WPLib\Components\Customizer;

/**
 * Class Settings_Model
 * @package Clubdeuce\WPLib\Components\Customizer
 */
class Settings_Model extends \WPLib_Model_Base {

    /**
     * @var array
     */
    protected $_settings;

    function __construct( array $args = array() ) {

        $theme = wp_get_theme();

        $args = wp_parse_args( array(
            'option_name' => sprintf( 'theme_mods_%1$s', $theme->get_stylesheet() ),
        ) );

        $args['settings'] = get_option( $args['option_name'] );

        parent::__construct( $args );

    }

    /**
     * @param  string $method_name
     * @param  array  $args
     * @return mixed|null
     */
    function __call( $method_name, $args ) {

        $value = null;

        if ( isset( $this->_settings[ $method_name ] ) ) {
            $value = $this->_settings[ $method_name ];
        }

        return $value;

    }

}
