<?php
namespace BooklyCustomFields\Frontend\Modules\Booking;

use Bookly\Lib as BooklyLib;
use BooklyCustomFields\Lib\ProxyProviders\Local;
use BooklyCustomFields\Lib\Captcha\Captcha;

/**
 * Class Components
 * @package BooklyCustomFields\Frontend\Modules\Booking
 */
class Components extends BooklyLib\Base\Components
{
    /**
     * Render custom fields at details step.
     *
     * @param BooklyLib\UserBookingData $userData
     */
    public function renderDetailsStep( BooklyLib\UserBookingData $userData )
    {
        $cf_data = array();

        if ( BooklyLib\Config::customFieldsPerService() ) {
            // Prepare custom fields data per service.
            foreach ( $userData->cart->getItems() as $cart_key => $cart_item ) {
                $data = array();
                $service_id = $cart_item->getServiceId();
                $key = get_option( 'bookly_custom_fields_merge_repeating' ) ? $service_id : $cart_key;

                if ( ! isset( $cf_data[ $key ] ) ) {
                    foreach ( $cart_item->getCustomFields() as $field ) {
                        $data[ $field['id'] ] = $field['value'];
                    }
                    if ( $cart_item->getService()->isCompound() ) {
                        $custom_fields = array();
                        // Collect custom fields for compound service.
                        foreach ( $cart_item->getService()->getSubServices() as $sub_service ) {
                            foreach ( Local::getTranslated( $sub_service->getId() ) as $field ) {
                                if ( ! array_key_exists( $field->id, $custom_fields ) ) {
                                    $custom_fields[ $field->id ] = $field;
                                }
                            }
                        }
                        $custom_fields = array_values( $custom_fields );
                    } else {
                        $custom_fields = Local::getTranslated( $service_id );
                    }

                    if ( ! BooklyLib\Config::filesEnabled() ) {
                        $custom_fields = array_filter( $custom_fields, function ( $field ) {
                            return $field->type != 'file';
                        } );
                    }
                    $cf_data[ $key ] = array(
                        'service_title' => BooklyLib\Entities\Service::find( $cart_item->getServiceId() )->getTranslatedTitle(),
                        'custom_fields' => $custom_fields,
                        'data'          => $data,
                    );
                }
            }
        } else {
            $cart_items = $userData->cart->getItems();
            $cart_item  = array_pop( $cart_items );
            $data       = array();
            foreach ( $cart_item->getCustomFields() as $field ) {
                $data[ $field['id'] ] = $field['value'];
            }
            $custom_fields = Local::getTranslated( null );
            if ( ! BooklyLib\Config::filesEnabled() ) {
                $custom_fields = array_filter( $custom_fields, function ( $field ) {
                    return $field->type != 'file';
                } );
            }
            $cf_data[] = array(
                'custom_fields' => $custom_fields,
                'data'          => $data,
            );
        }

        if ( strpos( get_option( 'bookly_custom_fields_data' ), '"captcha"' ) !== false ) {
            // Init Captcha.
            Captcha::init( $userData->getFormId() );
        }

        $show_service_title = BooklyLib\Config::customFieldsPerService() && count( $cf_data ) > 1;

        $captcha_url = admin_url( sprintf(
            'admin-ajax.php?action=bookly_custom_fields_captcha&csrf_token=%s&form_id=%s&%f',
            BooklyLib\Utils\Common::getCsrfToken(),
            $userData->getFormId(),
            microtime( true )
        ) );

        $this->render( '_6_details', compact( 'cf_data', 'show_service_title', 'captcha_url' ) );
    }
}