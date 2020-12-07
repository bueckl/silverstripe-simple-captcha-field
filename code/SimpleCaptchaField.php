<?php

/**
 * @author Itayi Patrick Chitovoro <patrick@chitosystems.com>
 * @copyright Copyright (c) 2015, Chito Systems (Pvt) Ltd
 * @package form
 * @subpackage validation
 */

namespace SilverstripeSimpleCaptcha;

use SilverStripe\Control\Session;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;

class SimpleCaptchaField extends TextField
{

    protected $validateOnSubmit = false;

    /**
     * SimpleCaptchaField constructor.
     * @param string $name
     * @param null $title
     * @param string $form
     */
    public function __construct($name, $title = null, $form)
    {
        Requirements::css( 'chitosystems/silverstripe-simple-captcha-field:/css/form.css');
        Requirements::javascript('chitosystems/silverstripe-simple-captcha-field:/js/SimpleCaptchaField.js');

        parent::__construct($name, $title, null, null, $form);

        $formName = $form->getName();
        $form->addExtraClass($formName);
        Requirements::customScript(sprintf("var SIMPLECAPTCHAFORM = '%s'", $formName));

    }


    /**
     * @return string
     */
    public function getSkyImageLink()
    {
        $controller = SimpleCaptchaController::create();
        $controller->generateCaptchaID();
        return $controller->Link() . "image/?" . time();
    }

    protected $extraClasses = array('SimpleCaptchaField', 'text');

    /**
     * Validate this field
     *
     * @param Validator $validator
     * @return bool
     */
    public function validate($validator)
    {

        if (self::getValidateOnSubmit()) {
            return true;
        } else {

            $simpleCaptcha = Injector::inst()->get(SimpleCaptchaController::class);
            if (strtoupper($this->value) === $simpleCaptcha->getCaptchaID()) {
                return true;
            }
            $errormsg = sprintf("%s is wrong, Correct captcha is required", $this->value);
            $validator->validationError(
                $this->name, $errormsg,
                "validation"
            );

            $this->session()->set("SimpleCaptchaError", $errormsg);
            return false;

        }
    }

    public function setValidateOnSubmit($bol = false)
    {
        $this->validateOnSubmit = $bol;

        return $this;
    }

    public function getValidateOnSubmit()
    {
        return $this->validateOnSubmit;
    }

    public function session() {
        return Injector::inst()->get(Session::class);
    }
}