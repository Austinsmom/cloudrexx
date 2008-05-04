<?php
require_once ASCMS_CORE_MODULE_PATH.'/access/lib/AccessLib.class.php';


class Access_Blocks extends AccessLib
{
	public function Access()
	{
		$this->__construct();
	}

	public function __construct()
	{
		global $objTemplate;

		parent::__construct();

		$this->_objTpl = &$objTemplate;
	}

	function setCurrentlyOnlineUsers($gender = null)
	{
		$objFWUser = FWUser::getFWUserObject();
		$arrSettings = User_Setting::getSettings();

		$filter = array(
			'active'	=> true,
			'last_activity' => array(
				'>' => (time()-3600)
			)
		);
		if ($arrSettings['block_currently_online_users_pic']['status']) {
			$filter['picture'] = array('!=' => '');
		}

		if (!empty($gender)) {
			$filter['gender'] = 'gender_'.$gender;
		}

		if ($objUser = $objFWUser->objUser->getUsers(
			$filter,
			null,
			array(
				'last_activity'	=> 'desc',
				'username'		=> 'asc'
			),
			null,
			$limit = $arrSettings['block_currently_online_users']['value']
		)) {
			while (!$objUser->EOF) {
				$this->_objTpl->setVariable(array(
					'ACCESS_USER_ID'	=> $objUser->getId(),
					'ACCESS_USER_USERNAME'	=> htmlentities($objUser->getUsername(), ENT_QUOTES, CONTREXX_CHARSET)
				));

				$objUser->objAttribute->first();
				while (!$objUser->objAttribute->EOF) {
					$objAttribute = $objUser->objAttribute->getById($objUser->objAttribute->getId());
					$this->parseAttribute($objUser, $objAttribute->getId(), 0, false, false, false, false, false);
					$objUser->objAttribute->next();
				}

				$this->_objTpl->parse('access_currently_online_'.(!empty($gender) ? $gender.'_' : '').'members');

				$objUser->next();
			}
		} else {
			$this->_objTpl->hideBlock('access_currently_online_'.(!empty($gender) ? $gender.'_' : '').'members');
		}
	}

	function setLastActiveUsers($gender = null)
	{
		$arrSettings = User_Setting::getSettings();

		$filter['active'] = true;
		if ($arrSettings['block_last_active_users_pic']['status']) {
			$filter['picture'] = array('!=' => '');
		}

		if (!empty($gender)) {
			$filter['gender'] = 'gender_'.$gender;
		}

		$objFWUser = FWUser::getFWUserObject();
		if ($objUser = $objFWUser->objUser->getUsers(
			$filter,
			null,
			array(
				'last_activity'	=> 'desc',
				'username'		=> 'asc'
			),
			null,
			$limit = $arrSettings['block_last_active_users']['value']
		)) {
			while (!$objUser->EOF) {
				$this->_objTpl->setVariable(array(
					'ACCESS_USER_ID'	=> $objUser->getId(),
					'ACCESS_USER_USERNAME'	=> htmlentities($objUser->getUsername(), ENT_QUOTES, CONTREXX_CHARSET)
				));

				$objUser->objAttribute->first();
				while (!$objUser->objAttribute->EOF) {
					$objAttribute = $objUser->objAttribute->getById($objUser->objAttribute->getId());
					$this->parseAttribute($objUser, $objAttribute->getId(), 0, false, false, false, false, false);
					$objUser->objAttribute->next();
				}

				$this->_objTpl->parse('access_last_active_'.(!empty($gender) ? $gender.'_' : '').'members');

				$objUser->next();
			}
		} else {
			$this->_objTpl->hideBlock('access_last_active_'.(!empty($gender) ? $gender.'_' : '').'members');
		}
	}

	function setLatestRegisteredUsers($gender = null)
	{
		$arrSettings = User_Setting::getSettings();

		$filter['active'] = true;
		if ($arrSettings['block_latest_reg_users_pic']['status']) {
			$filter['picture'] = array('!=' => '');
		}

		if (!empty($gender)) {
			$filter['gender'] = 'gender_'.$gender;
		}

		$objFWUser = FWUser::getFWUserObject();
		if ($objUser = $objFWUser->objUser->getUsers(
			$filter,
			null,
			array(
				'regdate'	=> 'desc',
				'username'	=> 'asc'
			),
			null,
			$limit = $arrSettings['block_latest_reg_users']['value']
		)) {
			while (!$objUser->EOF) {
				$this->_objTpl->setVariable(array(
					'ACCESS_USER_ID'	=> $objUser->getId(),
					'ACCESS_USER_USERNAME'	=> htmlentities($objUser->getUsername(), ENT_QUOTES, CONTREXX_CHARSET)
				));

				$objUser->objAttribute->first();
				while (!$objUser->objAttribute->EOF) {
					$objAttribute = $objUser->objAttribute->getById($objUser->objAttribute->getId());
					$this->parseAttribute($objUser, $objAttribute->getId(), 0, false, false, false, false, false);
					$objUser->objAttribute->next();
				}

				$this->_objTpl->parse('access_latest_registered_'.(!empty($gender) ? $gender.'_' : '').'members');

				$objUser->next();
			}
		} else {
			$this->_objTpl->hideBlock('access_latest_registered_'.(!empty($gender) ? $gender.'_' : '').'members');
		}
	}

	function setBirthdayUsers($gender = null)
	{
		$arrSettings = User_Setting::getSettings();

		$filter = array(
			'active'	=> true,
			'birthday'	=> array(
				array(
					'>' => mktime(0,0,0,date('m'),date('d'),date('y')),
					'<'	=> mktime(0,0,0,date('m'),date('d'),date('y'))+86400
				)
			)
		);
		if ($arrSettings['block_birthday_users_pic']['status']) {
			$filter['picture'] = array('!=' => '');
		}

		if (!empty($gender)) {
			$filter['gender'] = 'gender_'.$gender;
		}

		$objFWUser = FWUser::getFWUserObject();
		if ($objUser = $objFWUser->objUser->getUsers(
			$filter,
			null,
			array(
				'regdate'	=> 'desc',
				'username'	=> 'asc'
			),
			null,
			$limit = $arrSettings['block_birthday_users']['value']
		)) {
			while (!$objUser->EOF) {
				$this->_objTpl->setVariable(array(
					'ACCESS_USER_ID'	=> $objUser->getId(),
					'ACCESS_USER_USERNAME'	=> htmlentities($objUser->getUsername(), ENT_QUOTES, CONTREXX_CHARSET)
				));

				$objUser->objAttribute->first();
				while (!$objUser->objAttribute->EOF) {
					$objAttribute = $objUser->objAttribute->getById($objUser->objAttribute->getId());
					$this->parseAttribute($objUser, $objAttribute->getId(), 0, false, false, false, false, false);
					$objUser->objAttribute->next();
				}

				$this->_objTpl->parse('access_birthday_'.(!empty($gender) ? $gender.'_' : '').'members');

				$objUser->next();
			}
		} else {
			$this->_objTpl->hideBlock('access_birthday_'.(!empty($gender) ? $gender.'_' : '').'members');
		}
	}

	function isSomeonesBirthdayToday()
	{
		$objFWUser = FWUser::getFWUserObject();
		if ($objFWUser->objUser->getUsers(
			array(
				'active'	=> true,
				'birthday'	=> array(
					array(
						'>' => mktime(0,0,0,date('m'),date('d'),date('y')),
						'<'	=> mktime(0,0,0,date('m'),date('d'),date('y'))+86400
					)
				)
			),
			null, null, null, $limit = 1
		)) {
			return true;
		} else {
			return false;
		}
	}

}