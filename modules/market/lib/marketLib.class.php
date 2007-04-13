<?PHP
/**
 * Market library
 * @copyright   CONTREXX CMS - ASTALAVISTA IT AG
 * @author      Astalavista Development Team <thun@astalavista.ch>
 * @package     contrexx
 * @subpackage  module_market
 * @todo        Edit PHP DocBlocks!
 */

/**
 * Includes
 */
require_once ASCMS_LIBRARY_PATH . '/FRAMEWORK/File.class.php';

/**
 * Market library
 *
 * External functions for the market
 * @copyright   CONTREXX CMS - ASTALAVISTA IT AG
 * @author      Astalavista Development Team <thun@astalavista.ch>
 * @package     contrexx
 * @subpackage  module_market
 * @todo        Edit PHP DocBlocks!
 */
class marketLibrary
{
	function getCategories() {

		global $objDatabase;

		$this->settings = $this->getSettings();

		if  ($this->settings['indexview']['value'] == 1) {
			$order = "name";
		} else {
			$order = "displayorder";
		}

		$objResultCategories = $objDatabase->Execute('SELECT * FROM '.DBPREFIX.'module_market_categories ORDER BY '.$order.'');
	   	if ($objResultCategories !== false){
		   	while (!$objResultCategories->EOF) {
		   		$this->categories[$objResultCategories->fields['id']]['id'] 				= $objResultCategories->fields['id'];
		   		$this->categories[$objResultCategories->fields['id']]['name'] 				= $objResultCategories->fields['name'];
		   		$this->categories[$objResultCategories->fields['id']]['description'] 		= $objResultCategories->fields['description'];
		   		$this->categories[$objResultCategories->fields['id']]['order'] 				= $objResultCategories->fields['displayorder'];
		   		$this->categories[$objResultCategories->fields['id']]['status'] 			= $objResultCategories->fields['status'];
		   		$objResultCategories->MoveNext();
		   	}
	   	}
	}



	function getEntries($orderBy, $where, $like) {

		global $objDatabase;

		if($orderBy != ''){
			$orderBy = 'ORDER BY '.contrexx_addslashes($orderBy);
		}

		if($where != '' && $like != ''){
			$where = "WHERE ".contrexx_addslashes($where)." LIKE ".contrexx_addslashes($like);
		}

		$objResultEntries = $objDatabase->Execute('SELECT * FROM '.DBPREFIX.'module_market '.$where.' '.$orderBy);
	   	if ($objResultEntries !== false){
		   	while (!$objResultEntries->EOF) {
		   		$this->entries[$objResultEntries->fields['id']]['id'] 				= $objResultEntries->fields['id'];
		   		$this->entries[$objResultEntries->fields['id']]['type'] 			= $objResultEntries->fields['type'];
		   		$this->entries[$objResultEntries->fields['id']]['title'] 			= $objResultEntries->fields['title'];
		   		$this->entries[$objResultEntries->fields['id']]['description'] 		= $objResultEntries->fields['description'];
		   		$this->entries[$objResultEntries->fields['id']]['premium'] 			= $objResultEntries->fields['premium'];
		   		$this->entries[$objResultEntries->fields['id']]['picture'] 			= $objResultEntries->fields['picture'];
		   		$this->entries[$objResultEntries->fields['id']]['catid'] 			= $objResultEntries->fields['catid'];
		   		$this->entries[$objResultEntries->fields['id']]['price'] 			= $objResultEntries->fields['price'];
		   		$this->entries[$objResultEntries->fields['id']]['regdate'] 			= $objResultEntries->fields['regdate'];
		   		$this->entries[$objResultEntries->fields['id']]['enddate'] 			= $objResultEntries->fields['enddate'];
		   		$this->entries[$objResultEntries->fields['id']]['userid'] 			= $objResultEntries->fields['userid'];
		   		$this->entries[$objResultEntries->fields['id']]['name'] 			= $objResultEntries->fields['name'];
		   		$this->entries[$objResultEntries->fields['id']]['email'] 			= $objResultEntries->fields['email'];
		   		$this->entries[$objResultEntries->fields['id']]['userdetails'] 		= $objResultEntries->fields['userdetails'];
		   		$this->entries[$objResultEntries->fields['id']]['status'] 			= $objResultEntries->fields['status'];
		   		$this->entries[$objResultEntries->fields['id']]['regkey'] 			= $objResultEntries->fields['regkey'];
		   		$this->entries[$objResultEntries->fields['id']]['spez_field_1'] 	= $objResultEntries->fields['spez_field_1'];
		   		$this->entries[$objResultEntries->fields['id']]['spez_field_2'] 	= $objResultEntries->fields['spez_field_2'];
		   		$this->entries[$objResultEntries->fields['id']]['spez_field_3'] 	= $objResultEntries->fields['spez_field_3'];
		   		$this->entries[$objResultEntries->fields['id']]['spez_field_4'] 	= $objResultEntries->fields['spez_field_4'];
		   		$this->entries[$objResultEntries->fields['id']]['spez_field_5'] 	= $objResultEntries->fields['spez_field_5'];
		   		$objResultEntries->MoveNext();
		   	}
	   	}
	}



	function countEntries($catId) {

		global $objDatabase, $objTemplate, $_ARRAYLANG;

		$objResultCount = $objDatabase->Execute('SELECT id FROM '.DBPREFIX.'module_market WHERE catid = '.contrexx_addslashes($catId).' AND status =1');
		if($objResultCount !== false){
			$count = $objResultCount->RecordCount();
		}

		return $count;
	}



	function getSettings(){
    	global $objDatabase, $_ARRAYLANG;

    	//get settings
		$objResult = $objDatabase->Execute("SELECT name, value, type FROM ".DBPREFIX."module_market_settings");
		if($objResult !== false){
			while(!$objResult->EOF){
				$settings[$objResult->fields['name']] = $objResult->fields['value'];
				$objResult->MoveNext();
			}
		}

		return $settings;
    }



    function insertEntry($backend){
    	global $objDatabase, $_ARRAYLANG, $_CORELANG;

    	if($_FILES['pic']['name'] != ""){
			$picture = $this->uploadPicture();
		}else{
			$picture = "";
		}

		if($picture != "error"){

			if($_POST['forfree'] == 1){
				$price = "forfree";
			}elseif($_POST['agreement'] == 1){
				$price = "agreement";
			}else{
				$price = contrexx_addslashes($_POST['price']);
			}

			$today = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$tempDays 	= date("d");
			$tempMonth 	= date("m");
			$tempYear 	= date("Y");
			$enddate  = mktime(0, 0, 0, $tempMonth, $tempDays+$_POST['days'],  $tempYear);

			if($backend == 1){
				$status 	= '1';
				$regdate	= $today;
				$key		= '';
			}else{
				$status 	= '0';
				$regdate	= '';
				$rand	  	= rand(10, 99);
				$key		= md5($rand.$today);
				$key		= substr($key,0 ,6);
			}

			$objResult = $objDatabase->Execute("INSERT INTO ".DBPREFIX."module_market SET
			                    type='".contrexx_addslashes($_POST['type'])."',
		                      	title='".contrexx_addslashes($_POST['title'])."',
		                      	description='".contrexx_addslashes($_POST['description'])."',
								premium='".contrexx_addslashes($_POST['premium'])."',
			                  	picture='".contrexx_addslashes($picture)."',
			                  	catid='".contrexx_addslashes($_POST['cat'])."',
			                  	price='".$price."',
			                  	regdate='".$regdate."',
			                  	enddate='".$enddate."',
			                  	userid='".contrexx_addslashes($_SESSION['auth']['userid'])."',
			                  	name='".contrexx_addslashes($_POST['name'])."',
			                  	email='".contrexx_addslashes($_POST['email'])."',
			                  	userdetails='".contrexx_addslashes($_POST['userdetails'])."',
			                  	spez_field_1='".contrexx_addslashes($_POST['spez_1'])."',
			                  	spez_field_2='".contrexx_addslashes($_POST['spez_2'])."',
			                  	spez_field_3='".contrexx_addslashes($_POST['spez_3'])."',
			                  	spez_field_4='".contrexx_addslashes($_POST['spez_4'])."',
			                  	spez_field_5='".contrexx_addslashes($_POST['spez_5'])."',
			                  	regkey='".$key."',
			                  	status='".$status."'");

			if($objResult !== false){
				$this->strOkMessage = $_ARRAYLANG['TXT_MARKET_ADD_SUCCESS'];
				if($backend == 0){
					$this->sendCodeMail($objDatabase->Insert_ID());
				}
			}else{
				$this->strErrMessage = $_CORELANG['TXT_DATABASE_QUERY_ERROR'];
			}
		}else{
			$this->strErrMessage = $_ARRAYLANG['TXT_MARKET_IMAGE_UPLOAD_ERROR'];
		}
    }



	function sendCodeMail($entryId){

		global $objDatabase, $_ARRAYLANG, $_CONFIG;

		//entrydata
		$objResult = $objDatabase->Execute("SELECT id, title, name, userid, email, regkey FROM ".DBPREFIX."module_market WHERE id='".contrexx_addslashes($entryId)."' LIMIT 1");
	    if ($objResult !== false) {
			while (!$objResult->EOF) {
				$entryMail			= $objResult->fields['email'];
				$entryName			= $objResult->fields['name'];
				$entryTitle			= $objResult->fields['title'];
				$entryUserid		= $objResult->fields['userid'];
				$entryKey			= $objResult->fields['regkey'];
				$objResult->MoveNext();
			};
		}

		//assesuserdata
		$objResult = $objDatabase->Execute("SELECT email, username FROM ".DBPREFIX."access_users WHERE id='".$entryUserid."' LIMIT 1");
	    if ($objResult !== false) {
			while (!$objResult->EOF) {
				$userUsername		= $objResult->fields['username'];
				$objResult->MoveNext();
			};
		}

		//get mail content n title
		$objResult = $objDatabase->Execute("SELECT title, content, active, mailcc FROM ".DBPREFIX."module_market_mail WHERE id='2'");
	    if ($objResult !== false) {
			while (!$objResult->EOF) {
				$mailTitle		= $objResult->fields['title'];
				$mailContent	= $objResult->fields['content'];
				$mailCC			= $objResult->fields['mailcc'];
				$mailOn			= $objResult->fields['active'];
				$objResult->MoveNext();
			};
		}


		$array = explode('; ',$mailCC);
		$url	= $_SERVER['SERVER_NAME'].ASCMS_PATH_OFFSET;
		$now 	= date(ASCMS_DATE_FORMAT);

		//replase placeholder
		$array_1 = array('[[EMAIL]]', '[[NAME]]', '[[TITLE]]', '[[ID]]', '[[CODE]]', '[[URL]]', '[[DATE]]', '[[USERNAME]]');
		$array_2 = array($entryMail, $entryName, $entryTitle, $entryId, $entryKey, $url, $now, $userUsername);


		for($x = 0; $x < 8; $x++){
		  $mailTitle = str_replace($array_1[$x], $array_2[$x], $mailTitle);
		}

		for($x = 0; $x < 8; $x++){
		  $mailContent = str_replace($array_1[$x], $array_2[$x], $mailContent);
		}

		//create mail
		$fromName	= "Administrator - ".$url;
		$fromMail	= "";
		$subject 	= $mailTitle;
		$message 	= $mailContent;

		if (@include_once ASCMS_LIBRARY_PATH.'/phpmailer/class.phpmailer.php') {
			$objMail = new phpmailer();

			if ($_CONFIG['coreSmtpServer'] > 0 && @include_once ASCMS_CORE_PATH.'/SmtpSettings.class.php') {
				$objSmtpSettings = new SmtpSettings();
				if (($arrSmtp = $objSmtpSettings->getSmtpAccount($_CONFIG['coreSmtpServer'])) !== false) {
					$objMail->IsSMTP();
					$objMail->Host = $arrSmtp['hostname'];
					$objMail->Port = $arrSmtp['port'];
					$objMail->SMTPAuth = true;
					$objMail->Username = $arrSmtp['username'];
					$objMail->Password = $arrSmtp['password'];
				}
			}

			$objMail->From = $fromMail;
			$objMail->FromName = $fromName;
			$objMail->AddReplyTo($fromMail);
			$objMail->Subject = $subject;
			$objMail->IsHTML(false);
			$objMail->Body = $message;

			if($mailOn == 1){
				$objMail->AddAddress($entryMail);
				$objMail->Send();
				$objMail->ClearAddresses();
			}

			// Email message
			foreach($array as $arrKey => $toCC) {
				// Email message
				if (!empty($toCC)) {
					$objMail->AddAddress($toCC);
					$objMail->Send();
					$objMail->ClearAddresses();
				}
			}
		}
	}



    function uploadPicture(){

		$status        	= "";
        $path			= "pictures/";

		//check file array
        if(isset($_FILES) && !empty($_FILES))
        {
        	//get file info
        	$tmpFile  		= $_FILES['pic']['tmp_name'];
           	$fileName 		= $_FILES['pic']['name'];
           	$fileSize 		= $_FILES['pic']['size'];

           	if($fileName != ""){
				//check extension
           		$info     = pathinfo($fileName);
				$exte     = $info['extension'];
				$exte     = (!empty($exte)) ? '.' . $exte : '';
				$part1    = substr($fileName, 0, strlen($fileName) - strlen($exte));
				$rand	  = rand(10, 99);
				$fileName = md5($rand.$fileName).$exte;

				//check file
				if(file_exists($this->mediaPath.$path.$fileName)){
					$fileName = $rand.$part1 . '_' . (time() + $x) . $exte;
					$fileName = md5($fileName).$exte;
				}

				//upload file
				if(@move_uploaded_file($tmpFile, $this->mediaPath.$path.$fileName)) {
					$objFile = new File();
                    $objFile->setChmod($this->mediaPath, $this->mediaWebPath, $path.$fileName);
					$status = $fileName;
				}else{
					$status = "error";
				}
			}else {
				$status = "error";
			}
        }

        return $status;
	}


	function removeEntry($array){

		global $objDatabase, $objTemplate, $_ARRAYLANG, $_CORELANG;

		foreach($array as $arrKey => $entryId) {
	   		$status = "";
	   		$objResult = $objDatabase->Execute('SELECT picture FROM '.DBPREFIX.'module_market WHERE id = '.$entryId.' LIMIT 1');
			if($objResult !== false){
				$picture = $objResult->fields('picture');
			}

			if($picture != ''){
				$objFile = new File();
				$status = $objFile->delFile($this->mediaPath, $this->mediaWebPath, "pictures/".$picture);
			}

			if($status != "error"){
				$objResultDel = $objDatabase->Execute('DELETE FROM '.DBPREFIX.'module_market WHERE id = '.$entryId.'');
				if($objResultDel !== false){
					$this->strOkMessage = $_ARRAYLANG['TXT_MARKET_DELETE_SUCCESS'];
				}else{
					$this->strErrMessage = $_CORELANG['TXT_DATABASE_QUERY_ERROR'];
				}
			}else{
				$this->strErrMessage = $_ARRAYLANG['TXT_MARKET_IMAGE_DELETE_ERROR'];
			}
		}
	}
}
