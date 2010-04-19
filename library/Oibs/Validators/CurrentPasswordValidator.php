<?php
class Oibs_Validators_CurrentPasswordValidator extends Zend_Validate_Abstract
{
	const MSG_URI = 'msgUri';
	
    protected $_messageTemplates = array(
        self::MSG_URI => "account-currentpassword-nomatch",
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
		
		$userpw = new Default_Model_User();
        
        // Why this function is called when it has not been implemented? :/
        //$saltLength = $userpw->getSaltCountByUsername($auth->getIdentity()->username);
        $data = $userpw->getUserRow($id)->toArray();
        // the length of the salt is really not that difficult to get :)
        $saltLength = strlen($data['password_salt_usr']); 

        // Gets user password data, saltLength 7 for backwards compatability
        if($saltLength == 7) {
            // This is just repetition, let's declare the variable above these statements.
            //$data = $userpw->getUserRow($id)->toArray();	
            $password = $data['password_usr'];
            $compared_password = md5($value);
        } else {
            // Repetition...
            //$data = $userpw->getUserRow($id)->toArray();	
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