<?php
/**
 * Class SimpleCaptchaController
 *
 * @author Itayi Patrick Chitovoro <patrick@chitosystems.com>
 * @copyright Copyright (c) 2015, Chito Systems (Pvt) Ltd
 * @package form
 * @subpackage validation
 */

namespace SilverstripeSimpleCaptcha;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injector;

class SimpleCaptchaController extends Controller
{
    private static $allowed_actions = array(
        'image',
        'validate',
    );

    public function Link($action = null)
    {
        return "simple-validation/$action";
    }


    /**
     * @return bool
     */
    public function validate()
    {
        $captcha = Convert::raw2sql($this->urlParams['ID']);
        if (strtoupper($captcha) === $this->getCaptchaID()) {
            return 'valid';
        }

        return 'notvalid';
    }

    /**
     * @return bool
     */
    public function image()
    {
        $this->getResponse()->addHeader("Cache-control", "no-cache");
        $str = $this->getCaptchaID();
        // Create an image from button.png
        $image = imagecreatefrompng(SIMPLE_FORM_CAPTCHA_PATH . '/images/button.png');

        // Set the font colour
        $colour = imagecolorallocate($image, 183, 178, 152);
        // Set the font
        $font = SIMPLE_FORM_CAPTCHA_PATH . '/font/anorexia.ttf';
        // Set a random integer for the rotation between -15 and 15 degrees
        $rotate = rand(-1, 10);
        // Create an image using our original image and adding the detail
        imagettftext($image, 20, $rotate, 18, 30, $colour, $font, $str);
        // Output the image as a png
        return imagepng($image);

    }

    public function generateCaptchaID()
    {
        // debug::show($time_stamp);
        // Create a random string, leaving out 'o' to avoid confusion with '0'
        $char = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 4));
        // Concatenate the random string onto the random numbers
        // The font 'Anorexia' doesn't have a character for '8', so the numbers will only go up to 7
        // '0' is left out to avoid confusion with 'O'
        $str = rand(1, 7) . rand(1, 7) . $char;
        // Set the session contents

        if($this->getRequest()->hasSession()) {
            $this->getRequest()->getSession()->set("simple_captcha_id", $str);
        } else {
            $this->session()->set("simple_captcha_id", $str);
        }

    }

    /**
     * @return array|null|Session
     */
    public function getCaptchaID()
    {
        return $this->session()->get("simple_captcha_id");
    }

    public function session()
    {
        $request = Injector::inst()->get(HTTPRequest::class);
        return $request->getSession();
    }

}