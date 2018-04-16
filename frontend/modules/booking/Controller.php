<?php
namespace BooklyCustomFields\Frontend\Modules\Booking;

use Bookly\Lib;
use BooklyCustomFields\Lib\Captcha\Captcha;

/**
 * Class Controller
 * @package BooklyCustomFields\Frontend\Modules\Booking
 */
class Controller extends Lib\Base\Controller
{
    /**
     * Get access permissions.
     *
     * @return array
     */
    protected function getPermissions()
    {
        return array( '_this' => 'anonymous' );
    }

    /**
     * Output a PNG image of captcha to browser.
     */
    public function executeCaptcha()
    {
        Captcha::draw( $this->getParameter( 'form_id' ) );
    }

    /**
     * Refresh captcha.
     */
    public function executeCaptchaRefresh()
    {
        Captcha::init( $this->getParameter( 'form_id' ) );
        wp_send_json_success( array( 'captcha_url' => admin_url( sprintf(
            'admin-ajax.php?action=bookly_custom_fields_captcha&csrf_token=%s&form_id=%s&%f',
            Lib\Utils\Common::getCsrfToken(),
            $this->getParameter( 'form_id' ),
            microtime( true )
        ) ) ) );
    }

    /**
     * Override parent method to register 'wp_ajax_nopriv_' actions too.
     *
     * @param bool $with_nopriv
     */
    protected function registerWpAjaxActions( $with_nopriv = false )
    {
        parent::registerWpAjaxActions( true );
    }
}