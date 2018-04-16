<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Utils\Common;
?>
<div class="tab-pane" id="bookly_settings_custom_fields">
    <form method="post" action="<?php echo esc_url( add_query_arg( 'tab', 'custom_fields' ) ) ?>">
        <?php Common::optionToggle( 'bookly_custom_fields_enabled', __( 'Custom Fields', 'bookly-custom-fields' ), __( 'Enable this setting to display custom fields on your booking form.', 'bookly-custom-fields' ) ) ?>
        <div class="panel-footer">
            <?php Common::csrf() ?>
            <?php Common::submitButton() ?>
            <?php Common::resetButton() ?>
        </div>
    </form>
</div>