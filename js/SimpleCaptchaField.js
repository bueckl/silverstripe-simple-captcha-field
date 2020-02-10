/** =============================
 * SimpleCaptchaField.js
 * * ===========================
 * @author Patrick Chito-voro
 * @copyright 2016 Chito Systems.
 *
 * ============================= */

(function ($) {
    "use strict";
    /*global jQuery, document, window*/
    $(document).ready(function () {
        SimpleCaptchaField.validate();
    });

}(jQuery));


var SimpleCaptchaField = function () {

    return {
        validate: function () {

            if ( SIMPLECAPTCHAFORM.length ) {


                $("form." + SIMPLECAPTCHAFORM + " input[type='submit']").on('click', function (e) {
                e.preventDefault();

                var Captcha = $("input.SimpleCaptchaField").val();
                var url = "simple-validation/validate/" + Captcha;

                $form = $(this).closest('form');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (results) {
                        $form.attr('data-recaptcha',results);
                        if (results === 'valid') {
                            $form.submit();
                            return true;
                        } else {
                            $('.input-captcha').append("<div class='message validation'>Correct captcha is required</div>");
                            return false;
                        }
                    },
                    error: function () {
                        alert('Sorry there has been an error');
                    }
                });

            });

                
            }            

        }
    }
}();