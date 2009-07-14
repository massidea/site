<?php 
class Oibs_Decorators_CaptchaDecorator extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
    	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$output = /*'<dl class="form_element">'
						<dt class="form_element_left">&nbsp;</dt>
						<dd class="form_element_right">
						<img src="'.$baseUrl.'/en/account/captcha" id="registration_captcha" alt="captcha" />
                        <a href="javascript:reloadCaptcha(\''.$baseUrl.'\');"><img src="/images/icon_refresh_small.png" alt="reload" /></a>
						</dd>';
					//</dl>
					// insert javascript hear to reload captchaimage. no hacks this time? ;p
                    */
                    '
                    <div style="clear: both;"></div>
                        <div class="form_element_center" style="background: #ddd url(\'/images/bg_captcha_div.png\') top center; background-repeat: repeat-x; width: 200px; margin-top: 10px; margin-bottom:20px; margin-left: 15em;">
                            <img src="'.$baseUrl.'/en/account/captcha" id="registration_captcha" alt="captcha" style="float:left;" />
                            <a href="javascript:reloadCaptcha(\''.$baseUrl.'\');"><img src="/images/icon_reload_registration.png" alt="reload" /></a>
                        </div>
                    <div style="clear: both;"></div>
                    ';
        return $output;
	}
}
?>