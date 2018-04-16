<?php
namespace BooklyCustomFields\Backend\Modules\Calendar;

use Bookly\Lib as BooklyLib;
use BooklyCustomFields\Lib\ProxyProviders\Local;

/**
 * Class Components
 * @package BooklyCustomFields\Backend\Modules\Calendar
 */
class Components extends BooklyLib\Base\Components
{
    /**
     * Render custom fields in customer details dialog.
     */
    public function renderCustomerDetailsDialog()
    {
        // Custom fields without captcha & text content field.
        $custom_fields = Local::getWhichHaveData();

        if ( ! BooklyLib\Config::filesActive() ) {
            $custom_fields = array_filter( $custom_fields, function ( $field ) {
                return $field->type != 'file';
            } );
        }

        $this->render( '_customer_details_dialog', compact( 'custom_fields' ) );
    }
}