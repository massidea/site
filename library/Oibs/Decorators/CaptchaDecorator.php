<?php 
class Oibs_Decorators_CaptchaDecorator extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $translate = Zend_Registry::get('Zend_Translate'); 
        $t1 = $translate->translate('register-reload');
        $t2 = $translate->translate('register-letters');
    	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$output =  '
                    <div style="clear: both;">&nbsp;</div>
                    <div class="register_captcha">
                        <div style="float: left;">
                            <img src="'.$baseUrl.'/en/account/captcha" id="registration_captcha" alt="captcha" />
                        </div>
                        <div style="float: left; padding-left: 10px; padding-top:15px; font-weight: bold;">
                            < <a href="javascript:reloadCaptcha(\''.$baseUrl.'\');">'.$t1.'</a> '.$t2.'
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    ';
        return $output;
	}
}
?>