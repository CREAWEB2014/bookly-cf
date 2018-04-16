<?php
namespace BooklyCustomFields\Lib;

use Bookly\Lib;

/**
 * Class Installer
 * @package BooklyCustomFields\Lib
 */
class Installer extends Lib\Base\Installer
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Load l10n for fixtures creating.
        load_plugin_textdomain( Plugin::getTextDomain(), false, Plugin::getSlug() . '/languages' );

        $this->options = array(
            'bookly_custom_fields_data'            => '[]',
            'bookly_custom_fields_per_service'     => '0',
            'bookly_custom_fields_merge_repeating' => '1',
        );
    }
}