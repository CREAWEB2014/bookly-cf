<?php
namespace BooklyCustomFields\Backend\Modules\Settings;

use BooklyCustomFields\Lib;

/**
 * Class Controller
 * @package BooklyCustomFields\Backend\Modules\Settings
 */
class Controller extends \Bookly\Lib\Base\Controller
{
    public function renderSettingsForm()
    {
        $this->render( 'settings_form' );
    }

}