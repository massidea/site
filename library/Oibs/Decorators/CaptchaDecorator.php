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
			<div id="save_changes">
				<div id="captcha">
					<img src="'.$baseUrl.'/en/account/captcha" id="registration_captcha" alt="captcha" />
					<strong>< <a href="javascript:reloadCaptcha(\''.$baseUrl.'\');">'.$t1.'</a> '.$t2.'</strong>
				</div>
			</div>
			<div class="clear"></div>
                    ';
        return $output;
	}
}
?>