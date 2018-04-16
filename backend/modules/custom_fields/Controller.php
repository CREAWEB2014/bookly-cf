<?php
namespace BooklyCustomFields\Backend\Modules\CustomFields;

use Bookly\Lib;

/**
 * Class Controller
 * @package BooklyCustomFields\Backend\Modules\CustomFields
 */
class Controller extends Lib\Base\Controller
{
    const page_slug = 'bookly-custom-fields';

    /**
     *  Render page.
     */
    public function index()
    {
        $this->enqueueStyles( array(
            'bookly' => array(
                'backend/resources/bootstrap/css/bootstrap-theme.min.css',
                'frontend/resources/css/ladda.min.css',
            ),
        ) );

        $this->enqueueScripts( array(
            'bookly' => array(
                'backend/resources/bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'backend/resources/js/help.js'  => array( 'jquery' ),
                'backend/resources/js/alert.js'  => array( 'jquery' ),
                'frontend/resources/js/spin.min.js' => array( 'jquery' ),
                'frontend/resources/js/ladda.min.js' => array( 'jquery' ),
            ),
            'module' => array( 'js/custom_fields.js' => array( 'jquery-ui-sortable' ) ),
        ) );

        wp_localize_script( 'bookly-custom_fields.js', 'BooklyCustomFieldsL10n', array(
            'csrf_token' => Lib\Utils\Common::getCsrfToken(),
            'custom_fields' => get_option( 'bookly_custom_fields_data' ),
            'saved'    => __( 'Settings saved.', 'bookly-custom-fields' ),
            'selector' => array(
                'all_selected'     => __( 'All services', 'bookly-custom-fields' ),
                'nothing_selected' => __( 'No service selected', 'bookly-custom-fields' ),
            ),
        ) );

        $services = Lib\Entities\Service::query()
            ->select( 'id, title' )
            ->where( 'type', Lib\Entities\Service::TYPE_SIMPLE )
            ->fetchArray();

        $this->render( 'index', array( 'services_html' => $this->render( '_services', compact( 'services' ), false ) ) );
    }

    /**
     * Save custom fields.
     */
    public function executeSaveCustomFields()
    {
        $fields = $this->getParameter( 'fields', '[]' );
        $per_service     = (int) $this->getParameter( 'per_service' );
        $merge_repeating = (int) $this->getParameter( 'merge_repeating' );
        $custom_fields   = json_decode( $fields, true );

        foreach ( $custom_fields as $custom_field ) {
            switch ( $custom_field['type'] ) {
                case 'textarea':
                case 'text-content':
                case 'text-field':
                case 'captcha':
                case 'file':
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf(
                            'custom_field_%d_%s',
                            $custom_field['id'],
                            sanitize_title( $custom_field['label'] )
                        ),
                        $custom_field['label']
                    );
                    break;
                case 'checkboxes':
                case 'radio-buttons':
                case 'drop-down':
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf(
                            'custom_field_%d_%s',
                            $custom_field['id'],
                            sanitize_title( $custom_field['label'] )
                        ),
                        $custom_field['label']
                    );
                    foreach ( $custom_field['items'] as $label ) {
                        do_action(
                            'wpml_register_single_string',
                            'bookly',
                            sprintf(
                                'custom_field_%d_%s=%s',
                                $custom_field['id'],
                                sanitize_title( $custom_field['label'] ),
                                sanitize_title( $label )
                            ),
                            $label
                        );
                    }
                    break;
            }
        }

        Lib\Proxy\Files::saveCustomFields( $custom_fields );

        update_option( 'bookly_custom_fields_data', $fields );
        update_option( 'bookly_custom_fields_per_service', $per_service );
        update_option( 'bookly_custom_fields_merge_repeating', $merge_repeating );
        wp_send_json_success();
    }
}