<?php
class Oibs_Validators_CurrentPasswordValidator extends Zend_Validate_Abstract
{
	const MSG_URI = 'msgUri';
	
    protected $_messageTemplates = array(
        self::MSG_URI => "Current password does not match",
    );
	
	/**
	*	Compares the given password into the password that is in the database
	*	@param $value, $context
	*	@return boolean
	*/
	public function isValid($value, $context = null)
	{
		$this->_setValue($value);
		
		//  Get user identity
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
			
		// User id
		$id = $identity->user_id;
		
		$userpw = new Models_User();
        $saltLength = $userpw->getSaltCountByUsername($auth->getIdentity()->username);
        // Gets user password data, saltLength 7 for backwards compatability
        if($saltLength == 7) {
            $data = $userpw->getUserRow($id)->toArray();	
            $password = $data['password_usr'];
            $compared_password = md5($value);
        } else {
            $data = $userpw->getUserRow($id)->toArray();	
            $password = $data['password_usr'];
            $compared_password = md5($data['password_salt_usr'].$value.$data['password_salt_usr']);
        }
		// compares the two password hashes
		if($compared_password != $password)
		{
            $this->_error(self::MSG_URI);
			return false;
		} // end if
		
		return true;
	} // end isValid()
}
?>