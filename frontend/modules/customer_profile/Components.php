<?php
namespace BooklyCustomFields\Frontend\Modules\CustomerProfile;

use Bookly\Lib;
use BooklyCustomFields\Lib\ProxyProviders\Local;

/**
 * Class Components
 * @package BooklyCustomFields\Frontend\Modules\CustomerProfile
 */
class Components extends Lib\Base\Components
{
    /**
     * Render row in customer profile.
     *
     * @param array $field_ids
     * @param array $appointment_data
     */
    public function renderCustomFieldsRow( $field_ids, $appointment_data )
    {
        $field_values = array();
        $ca = new Lib\Entities\CustomerAppointment( $appointment_data );
        foreach ( Local::getForCustomerAppointment( $ca, true ) as $field ) {
            $field_values[ $field['id'] ] = $field['value'];
        }

        $this->render( '_custom_fields', compact( 'field_ids', 'field_values' ) );
    }
}