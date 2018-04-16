<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Utils\Common;
use Bookly\Backend\Modules\Support;
?>
<div id="bookly-tbs" class="wrap">
    <div class="bookly-tbs-body">
        <div class="page-header text-right clearfix">
            <div class="bookly-page-title">
                <?php _e( 'Custom Fields', 'bookly-custom-fields' ) ?>
            </div>
            <?php Support\Components::getInstance()->renderButtons( $this::page_slug ) ?>
        </div>
        <div class="panel panel-default bookly-main">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5">
                        <?php Common::optionToggle( 'bookly_custom_fields_per_service', __( 'Bind fields to services', 'bookly-custom-fields' ), __( 'When this setting is enabled you will be able to create service specific custom fields.', 'bookly-custom-fields' ) ) ?>
                    </div>
                    <div class="col-md-5 bookly-services-holder" style="display: none">
                        <?php Common::optionToggle( 'bookly_custom_fields_merge_repeating', __( 'Merge repeating custom fields for multiple bookings of the service', 'bookly-custom-fields' ), __( 'If enabled, customers will see custom fields for unique appointments while booking multiple instances of the service. Repeating custom fields are merged (collapsed) into one field. If disabled, customers will see custom fields for each appointment in the set of bookings.', 'bookly-custom-fields' ) ) ?>
                    </div>
                </div>

                <hr />

                <ul id="bookly-custom-fields"></ul>

                <div id="bookly-js-add-fields">
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="text-field"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Text Field', 'bookly-custom-fields' ) ?></button>
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="textarea"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Text Area', 'bookly-custom-fields' ) ?></button>
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="text-content"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Text Content', 'bookly-custom-fields' ) ?></button>
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="checkboxes"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Checkbox Group', 'bookly-custom-fields' ) ?></button>
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="radio-buttons"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Radio Button Group', 'bookly-custom-fields' ) ?></button>
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="drop-down"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Drop Down', 'bookly-custom-fields' ) ?></button>
                    <button class="btn btn-default bookly-margin-bottom-sm bookly-margin-right-sm" data-type="captcha"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Captcha', 'bookly-custom-fields' ) ?></button>
                    <?php Bookly\Lib\Proxy\Files::renderCustomFieldButton() ?>
                </div>
                <small><?php _e( 'HTML allowed in all texts and labels.', 'bookly-custom-fields' ) ?></small>

                <ul id="bookly-templates" style="display:none">

                    <li data-type="textarea">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Text Area', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                    title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="bookly-label form-control" type="text" value="" placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                                            <label class="input-group-addon">
                                                <input class="bookly-required" type="checkbox">
                                                <span class="hidden-xs"><?php _e( 'Required field', 'bookly-custom-fields' ) ?></span>
                                                <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="text-content">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Text Content', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                    title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <textarea class="bookly-label form-control" type="text" rows="3"
                                                  placeholder="<?php esc_attr_e( 'Enter a content', 'bookly-custom-fields' ) ?>"></textarea>
                                        <input class="bookly-required hidden" type="checkbox" disabled="disabled">
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="text-field">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Text Field', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                    title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="bookly-label form-control" type="text" value=""
                                                   placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                                            <label class="input-group-addon">
                                                <input class="bookly-required" type="checkbox">
                                                <span class="hidden-xs"><?php _e( 'Required field', 'bookly-custom-fields' ) ?></span>
                                                <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="checkboxes">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Checkbox Group', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                   title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="bookly-label form-control" type="text" value=""
                                                   placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                                            <label class="input-group-addon">
                                                <input class="bookly-required" type="checkbox">
                                                <span class="hidden-xs"><?php _e( 'Required field', 'bookly-custom-fields' ) ?></span>
                                                <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                            </label>
                                        </div>

                                        <ul class="bookly-items bookly-margin-top-sm"></ul>
                                        <button class="btn btn-sm btn-default" data-type="checkboxes-item">
                                            <i class="glyphicon glyphicon-plus"></i> <?php _e( 'Checkbox', 'bookly-custom-fields' ) ?>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="radio-buttons">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Radio Button Group', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                    title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="bookly-label form-control" type="text" value=""
                                                   placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                                            <label class="input-group-addon">
                                                <input class="bookly-required" type="checkbox">
                                                <span class="hidden-xs"><?php _e( 'Required field', 'bookly-custom-fields' ) ?></span>
                                                <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                            </label>
                                        </div>

                                        <ul class="bookly-items bookly-margin-top-sm"></ul>
                                        <button class="btn btn-sm btn-default" data-type="radio-buttons-item">
                                            <i class="glyphicon glyphicon-plus"></i> <?php _e( 'Radio Button', 'bookly-custom-fields' ) ?>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="drop-down">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Drop Down', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                    title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="bookly-label form-control" type="text" value=""
                                                   placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                                            <label class="input-group-addon">
                                                <input class="bookly-required" type="checkbox">
                                                <span class="hidden-xs"><?php _e( 'Required field', 'bookly-custom-fields' ) ?></span>
                                                <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                            </label>
                                        </div>

                                        <ul class="bookly-items bookly-margin-top-sm"></ul>
                                        <button class="btn btn-sm btn-default" data-type="drop-down-item">
                                            <i class="glyphicon glyphicon-plus"></i> <?php _e( 'Option', 'bookly-custom-fields' ) ?>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="captcha">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell" style="width: 100%">
                                <p><b><?php _e( 'Captcha', 'bookly-custom-fields' ) ?></b><a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                    title="<?php esc_attr_e( 'Remove field', 'bookly-custom-fields' ) ?>"></a></p>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input class="bookly-label form-control" type="text" value=""
                                                   placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                                            <label class="input-group-addon">
                                                <input class="bookly-required hidden" type="checkbox">
                                                <input type="checkbox" disabled="disabled" checked="checked">
                                                <span class="hidden-xs"><?php _e( 'Required field', 'bookly-custom-fields' ) ?></span>
                                                <i class="visible-xs-inline-block glyphicon glyphicon-warning"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo $services_html ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </li>

                    <li data-type="checkboxes-item">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell bookly-vertical-middle">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell bookly-vertical-middle" style="width: 100%">
                                <input class="form-control" type="text" value=""
                                       placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                            </div>
                            <div class="bookly-flex-cell bookly-vertical-middle">
                                <a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                   title="<?php esc_attr_e( 'Remove item', 'bookly-custom-fields' ) ?>"></a>
                            </div>
                        </div>
                    </li>

                    <li data-type="radio-buttons-item">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell bookly-vertical-middle">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell bookly-vertical-middle" style="width: 100%">
                                <input class="form-control" type="text" value=""
                                       placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                            </div>
                            <div class="bookly-flex-cell bookly-vertical-middle">
                                <a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                   title="<?php esc_attr_e( 'Remove item', 'bookly-custom-fields' ) ?>"></a>
                            </div>
                        </div>
                    </li>

                    <li data-type="drop-down-item">
                        <div class="bookly-flexbox">
                            <div class="bookly-flex-cell bookly-vertical-middle">
                                <i title="<?php esc_attr_e( 'Reorder', 'bookly-custom-fields' ) ?>" class="bookly-js-handle bookly-icon bookly-icon-draghandle bookly-margin-right-sm bookly-cursor-move"></i>
                            </div>
                            <div class="bookly-flex-cell bookly-vertical-middle" style="width: 100%">
                                <input class="form-control" type="text" value=""
                                       placeholder="<?php esc_attr_e( 'Enter a label', 'bookly-custom-fields' ) ?>">
                            </div>
                            <div class="bookly-flex-cell bookly-vertical-middle">
                                <a class="bookly-js-delete glyphicon glyphicon-trash text-danger bookly-margin-left-sm" href="#"
                                   title="<?php esc_attr_e( 'Remove item', 'bookly-custom-fields' ) ?>"></a>
                            </div>
                        </div>
                    </li>

                    <?php Bookly\Lib\Proxy\Files::renderCustomFieldTemplate( $services_html ) ?>
                </ul>
            </div>

            <div class="panel-footer">
                <?php Common::submitButton( 'ajax-send-custom-fields' ) ?>
                <?php Common::resetButton() ?>
            </div>
        </div>
    </div>
</div>