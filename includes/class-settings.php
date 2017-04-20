<?php

namespace Clubdeuce\WPLib\Components\Customizer;

/**
 * Class Settings
 * @package Clubdeuce\WPLib\Components\Customizer
 */
class Settings extends \WPLib_Item_Base {

    /**
     * @param  string $property
     * @return mixed|void
     */
    function __get( $property ) {

        return get_option( $property );

    }

}
