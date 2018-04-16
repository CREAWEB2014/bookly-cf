<?php
namespace BooklyCustomFields\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Lib\NotificationCodes;
use BooklyCustomFields\Backend\Modules as Backend;
use BooklyCustomFields\Lib\Plugin;

/**
 * Class Shared
 * Provide shared methods to be used in Bookly.
 *
 * @package BooklyCustomFields\Lib\ProxyProviders
 */
abstract class Shared extends BooklyLib\Base\ProxyProvider
{

    /**
     * Prepare data for notification codes.
     *
     * @param NotificationCodes $codes
     */
    public static function prepareNotificationCodesForOrder( NotificationCodes $codes )
    {
        $codes->custom_fields    = Local::getFormatted( $codes->getItem()->getCA(), 'text' );
        $codes->custom_fields_2c = Local::getFormatted( $codes->getItem()->getCA(), 'html' );
    }

    /**
     * Prepare replacements for notification codes.
     *
     * @param array             $codes
     * @param NotificationCodes $notification_codes
     * @param string            $format
     * @return array
     */
    public static function prepareReplaceCodes( array $codes, NotificationCodes $notification_codes, $format )
    {
        $codes['{custom_fields}']    = $notification_codes->custom_fields;
        $codes['{custom_fields_2c}'] = $format == 'html' ? $notification_codes->custom_fields_2c : $notification_codes->custom_fields;

        return $codes;
    }

    /**
     * Add codes for displaying in notification templates.
     *
     * @param array $codes
     * @param string $type
     * @return array
     */
    public static function prepareNotificationCodes( array $codes, $type )
    {
        $codes['customer_appointment']['custom_fields'] = __( 'combined values of all custom fields', 'bookly-custom-fields' );
        $codes['staff_agenda']['next_day_agenda_extended'] = __( 'extended staff agenda for next day', 'bookly-custom-fields' );
        if ( $type == 'email' ) {
            $codes['customer_appointment']['custom_fields_2c'] = __( 'combined values of all custom fields (formatted in 2 columns)', 'bookly-custom-fields' );
        }

        return $codes;
    }

    /**
     * Add custom fields code to be displayed in calendar.
     *
     * @param array $codes
     * @param string $participants
     * @return array
     */
    public static function prepareCalendarAppointmentCodes( array $codes, $participants )
    {
        if ( $participants == 'one' ) {
            $codes[] = array( 'code' => 'custom_fields', 'description' => __( 'combined values of all custom fields', 'bookly-custom-fields' ) );
        }

        return $codes;
    }

    /**
     * Add {custom_fields} code in WooCommerce.
     *
     * @param array $codes
     * @return array
     */
    public static function prepareWooCommerceShortCodes( array $codes )
    {
        $codes[] = array( 'code' => 'custom_fields', 'description' => __( 'combined values of all custom fields', 'bookly-custom-fields' ) );

        return $codes;
    }

    /**
     * Add custom_fields for cart item
     *
     * @param array     $data
     * @param BooklyLib\CartItem $cart_item
     * @return array
     */
    public static function prepareCartItemInfoText( $data, BooklyLib\CartItem $cart_item )
    {
        if ( Plugin::enabled() ) {
            $data['custom_fields'] = Local::getForCartItem( $cart_item, true );
        }

        return $data;
    }

    /**
     * Add {custom_fields} code to booking
     *
     * @param array $info_text_codes
     * @param array $data
     * @return array
     */
    public static function prepareInfoTextCodes( array $info_text_codes, array $data )
    {
        if ( Plugin::enabled() ) {
            $info_text_codes['{custom_fields}'] = implode( '<br>', $data['custom_fields'] );
        }

        return $info_text_codes;
    }

    /**
     * Add data for custom fields code to be displayed in calender.
     *
     * @param array $codes
     * @param array $appointment_data
     * @param string $participants
     * @return array
     */
    public static function prepareCalendarAppointmentCodesData( array $codes, $appointment_data, $participants )
    {
        if ( $participants == 'one' ) {
            if ( $appointment_data['custom_fields'] != '[]' ) {
                $ca = new BooklyLib\Entities\CustomerAppointment();
                $ca->setCustomFields(  $appointment_data['custom_fields'] );
                $ca->setAppointmentId( $appointment_data['id'] );
                foreach ( Local::getForCustomerAppointment( $ca ) as $custom_field ) {
                    $codes['{custom_fields}'] .= sprintf( '<div>%s: %s</div>', wp_strip_all_tags( $custom_field['label'] ), nl2br( esc_html( $custom_field['value'] ) ) );
                }
            }
        }

        return $codes;
    }

    /**
     * Render custom fields settings in Bookly Settings.
     */
    public static function renderSettingsForm()
    {
        Backend\Settings\Controller::getInstance()->renderSettingsForm();
    }

    /**
     * Render custom fields menu in Bookly Settings.
     */
    public static function renderSettingsMenu()
    {
        printf( '<li class="bookly-nav-item" data-target="#bookly_settings_custom_fields" data-toggle="tab">%s</li>', __( 'Custom Fields', 'bookly-custom-fields' ) );
    }

    /**
     * Save settings.
     *
     * @param array  $alert
     * @param string $tab
     * @param array  $_post
     * @return array
     */
    public static function saveSettings( array $alert, $tab, $_post )
    {
        if ( $tab == 'custom_fields' && ! empty( $_post ) ) {
            $options = array( 'bookly_custom_fields_enabled' );
            foreach ( $options as $option_name ) {
                if ( array_key_exists( $option_name, $_post ) ) {
                    update_option( $option_name, $_post[ $option_name ] );
                }
            }
            $alert['success'][] = __( 'Settings saved.', 'bookly-custom-fields' );
        }

        return $alert;
    }
}