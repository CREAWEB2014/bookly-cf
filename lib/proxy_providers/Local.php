<?php
namespace BooklyCustomFields\Lib\ProxyProviders;

use Bookly\Lib;
use BooklyCustomFields\Lib\Plugin;
use BooklyCustomFields\Lib\Captcha\Captcha;
use BooklyCustomFields\Backend\Modules\CustomFields;
use BooklyCustomFields\Backend\Modules\Calendar;
use BooklyCustomFields\Frontend\Modules\Booking;
use BooklyCustomFields\Frontend\Modules\CustomerProfile;

/**
 * Class Local
 * Provide local methods to be used in Bookly and other add-ons.
 *
 * @package BooklyCustomFields\Lib\ProxyProviders
 */
abstract class Local extends Lib\Base\ProxyProvider
{
    /**
     * Get custom fields.
     *
     * @param array $exclude
     * @return \stdClass[]
     */
    public static function getAll( $exclude = array() )
    {
        $custom_fields = json_decode( get_option( 'bookly_custom_fields_data', '[]' ) );

        if ( ! empty ( $exclude ) ) {
            $custom_fields = array_filter( $custom_fields, function( $field ) use ( $exclude ) {
                return ! in_array( $field->type, $exclude );
            } );
        }

        return $custom_fields;
    }

    /**
     * Get custom fields for service.
     *
     * @param array $custom_fields
     * @param int   $service_id
     * @return array
     */
    public static function filterForService( array $custom_fields, $service_id )
    {
        if ( get_option( 'bookly_custom_fields_per_service' ) ) {
            $service_custom_fields = array();
            $custom_fields_data    = json_decode( get_option( 'bookly_custom_fields_data', '[]' ) );
            foreach ( $custom_fields_data as $key => $custom_field ) {
                if ( in_array( $service_id, $custom_field->services ) ) {
                    foreach ( $custom_fields as $field ) {
                        if ( $custom_field->id == $field['id'] ) {
                            $service_custom_fields[] = $field;
                        }
                    }
                }
            }

            return $service_custom_fields;
        } else {
            return $custom_fields;
        }
    }

    /**
     * Get translated custom fields.
     *
     * @param int $service_id
     * @param bool $translate
     * @param string $language_code
     * @return \stdClass[]
     */
    public static function getTranslated( $service_id = null, $translate = true, $language_code = null )
    {
        $custom_fields = json_decode( get_option( 'bookly_custom_fields_data', '[]' ) );
        foreach ( $custom_fields as $key => $custom_field ) {
            if ( $service_id === null || in_array( $service_id, $custom_field->services ) ) {
                switch ( $custom_field->type ) {
                    case 'textarea':
                    case 'text-content':
                    case 'text-field':
                    case 'captcha':
                    case 'file':
                        if ( $translate ) {
                            $custom_field->label = Lib\Utils\Common::getTranslatedString(
                                sprintf(
                                    'custom_field_%d_%s',
                                    $custom_field->id,
                                    sanitize_title( $custom_field->label )
                                ),
                                $custom_field->label,
                                $language_code
                            );
                        }
                        break;
                    case 'checkboxes':
                    case 'radio-buttons':
                    case 'drop-down':
                        $items = $custom_field->items;
                        foreach ( $items as $i => $label ) {
                            $items[ $i ] = array(
                                'label' => $translate
                                    ? Lib\Utils\Common::getTranslatedString(
                                        sprintf(
                                            'custom_field_%d_%s=%s',
                                            $custom_field->id,
                                            sanitize_title( $custom_field->label ),
                                            sanitize_title( $label )
                                        ),
                                        $label,
                                        $language_code
                                    )
                                    : $label,
                                'value' => $label
                            );
                        }
                        $custom_field->items = $items;
                        if ( $translate ) {
                            $custom_field->label = Lib\Utils\Common::getTranslatedString(
                                sprintf(
                                    'custom_field_%d_%s',
                                    $custom_field->id,
                                    sanitize_title( $custom_field->label )
                                ),
                                $custom_field->label,
                                $language_code
                            );
                        }
                        break;
                }
            } else {
                unset ( $custom_fields[ $key ] );
            }
        }

        return $custom_fields;
    }

    /**
     * Get custom fields which may have data (no Captcha and Text Content).
     *
     * @return \stdClass[]
     */
    public static function getWhichHaveData()
    {
        return self::getAll( array( 'captcha', 'text-content' ) );
    }

    /**
     * Get custom fields data for given customer appointment.
     *
     * @param Lib\Entities\CustomerAppointment $ca
     * @param bool $translate
     * @param null $locale
     * @return array
     */
    public static function getForCustomerAppointment( Lib\Entities\CustomerAppointment $ca, $translate = false, $locale = null )
    {
        $result = self::_getData( (array) json_decode( $ca->getCustomFields(), true ), $translate, $locale );

        return $result;
    }

    /**
     * Get custom fields data for given cart item.
     *
     * @param Lib\CartItem $cart_item
     * @param bool $translate
     * @param null $locale
     * @return array
     */
    public static function getForCartItem( Lib\CartItem $cart_item, $translate = false, $locale = null )
    {
        $result = array();
        $fields = self::_getData( $cart_item->getCustomFields(), $translate, $locale );
        foreach ( $fields as $field ) {
            $result[] = $field['label'] . ': ' . $field['value'];
        }

        return $result;
    }

    /**
     * Get custom fields data.
     *
     * @param array $customer_custom_fields
     * @param bool  $translate
     * @param null  $locale
     * @return array
     */
    private static function _getData( array $customer_custom_fields, $translate = false, $locale = null )
    {
        $result = array();
        if ( $customer_custom_fields ) {
            $custom_fields = array();
            $cf = Local::getTranslated( null, $translate, $locale );
            foreach ( $cf as $field ) {
                $custom_fields[ $field->id ] = $field;
            }
            $data = Lib\Proxy\Files::setFileNamesForCustomFields( $customer_custom_fields, $custom_fields );

            foreach ( $data as $customer_custom_field ) {
                if ( array_key_exists( $customer_custom_field['id'], $custom_fields ) ) {
                    $field = $custom_fields[ $customer_custom_field['id'] ];
                    $translated_value = array();
                    if ( array_key_exists( 'value', $customer_custom_field ) ) {
                        // Custom field have items ( radio group, etc. )
                        if ( property_exists( $field, 'items' ) ) {
                            foreach ( $field->items as $item ) {
                                // Customer select many values ( checkbox )
                                if ( is_array( $customer_custom_field['value'] ) ) {
                                    foreach ( $customer_custom_field['value'] as $field_value ) {
                                        if ( $item['value'] == $field_value ) {
                                            $translated_value[] = $item['label'];
                                        }
                                    }
                                } elseif ( $item['value'] == $customer_custom_field['value'] ) {
                                    $translated_value[] = $item['label'];
                                }
                            }
                        } else {
                            $translated_value[] = $customer_custom_field['value'];
                        }
                    }
                    $result[] = array(
                        'id'    => $customer_custom_field['id'],
                        'label' => $field->label,
                        'value' => implode( ', ', $translated_value )
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Get formatted custom fields.
     *
     * @param Lib\Entities\CustomerAppointment $ca
     * @param string $format
     * @param string $locale
     * @return string
     */
    public static function getFormatted( Lib\Entities\CustomerAppointment $ca, $format, $locale = null )
    {
        $result = '';
        switch ( $format ) {
            case 'html':
                foreach ( self::getForCustomerAppointment( $ca, true, $locale ) as $custom_field ) {
                    if ( $custom_field['value'] != '' ) {
                        $result .= sprintf(
                            '<tr valign=top><td>%s:&nbsp;</td><td>%s</td></tr>',
                            $custom_field['label'], $custom_field['value']
                        );
                    }
                }
                if ( $result != '' ) {
                    $result = "<table cellspacing=0 cellpadding=0 border=0>$result</table>";
                }
                break;

            case 'text':
                foreach ( self::getForCustomerAppointment( $ca, true, $locale ) as $custom_field ) {
                    if ( $custom_field['value'] != '' ) {
                        $result .= sprintf(
                            "%s: %s\n",
                            $custom_field['label'], $custom_field['value']
                        );
                    }
                }
                break;
        }

        return $result;
    }

    /**
     * Validate custom fields.
     *
     * @param array $errors
     * @param string $value
     * @param int $form_id
     * @param int $cart_key
     * @return array
     */
    public static function validate( array $errors, $value, $form_id, $cart_key )
    {
        $decoded_value = json_decode( $value );
        $fields = array();
        foreach ( json_decode( get_option( 'bookly_custom_fields_data' ) ) as $field ) {
            $fields[ $field->id ] = $field;
        }

        foreach ( $decoded_value as $field ) {
            if ( isset ( $fields[ $field->id ] ) ) {
                if ( ( $fields[ $field->id ]->type == 'captcha' ) && ! Captcha::validate( $form_id, $field->value ) ) {
                    $errors['custom_fields'][ $cart_key ][ $field->id ] = __( 'Incorrect code', 'bookly-custom-fields' );
                } elseif ( $fields[ $field->id ]->required && empty ( $field->value ) && $field->value != '0' ) {
                    $errors['custom_fields'][ $cart_key ][ $field->id ] = __( 'Required', 'bookly-custom-fields' );
                } else {
                    /**
                     * Custom field validation for a third party,
                     * if the value is not valid then please add an error message like in the above example.
                     *
                     * @param \stdClass
                     * @param ref array
                     * @param string
                     * @param \stdClass
                     */
                    do_action_ref_array( 'bookly_validate_custom_field', array( $field, &$errors, $cart_key, $fields[ $field->id ] ) );
                }
            }
        }

        return $errors;
    }

    /**
     * Render custom fields in customer details dialog.
     */
    public static function renderCustomerDetails()
    {
        Calendar\Components::getInstance()->renderCustomerDetailsDialog();
    }

    /**
     * Render custom fields at Details step.
     *
     * @param Lib\UserBookingData $userData
     */
    public static function renderDetailsStep( Lib\UserBookingData $userData )
    {
        if ( Plugin::enabled() ) {
            Booking\Components::getInstance()->renderDetailsStep( $userData );
        }
    }

    /**
     * Render custom fields in customer profile.
     *
     * @param array $field_ids
     * @param array $appointment_data
     */
    public static function renderCustomerProfileRow( array $field_ids, array $appointment_data )
    {
        CustomerProfile\Components::getInstance()->renderCustomFieldsRow( $field_ids, $appointment_data );
    }

    /**
     * Add 'Custom Fields' to Bookly menu.
     */
    public static function addBooklyMenuItem()
    {
        $custom_fields  = __( 'Custom Fields', 'bookly-custom-fields' );

        add_submenu_page( 'bookly-menu', $custom_fields, $custom_fields, 'manage_options',
            CustomFields\Controller::page_slug, array( CustomFields\Controller::getInstance(), 'index' ) );
    }
}