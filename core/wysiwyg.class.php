<?php
//Security-Check
if (eregi('wysiwyg.class.php',$_SERVER['PHP_SELF']))
{
    header('Location: index.php');
    exit;
}

// set wysiwyg editor
$wysiwygEditor = 'FCKeditor';


// initialize variables
switch ($wysiwygEditor) {
	case 'FCKeditor':
		$FCKeditorBasePath = '/editor/fckeditor/';
		break;
}

/**
 * WYSIWYG editor
 *
 * Gets the HTML code for the wysiwyg editor as a string
 * @version   1.0        initial version
 * @return string The WYSIWYG editor code
 */
function get_wysiwyg_code()
{
	global $wysiwygEditor;

	$return = '';

	switch ($wysiwygEditor) {
		case 'FCKeditor':
			global $FCKeditorBasePath;

			$return = '';
			break;
	}
	return $return;
}


/**
 * WYSIWYG editor
 *
 * Gets the wysiwyg editor as a string
 * @version   1.0        initial version
 * @return string
 * @param string $name
 * @param string $value
 * @param string $mode
 */
function get_wysiwyg_editor($name, $value = '', $mode = '', $languageId = null, $absoluteURIs = false)
{
	global $wysiwygEditor;

	switch ($wysiwygEditor) {
		case 'FCKeditor':
			global $FCKeditorBasePath;

			$include_path = ASCMS_DOCUMENT_ROOT.$FCKeditorBasePath;
			require_once($include_path.'fckeditor.php');

			$objFCKeditor = new FCKeditor($name) ;
			$objFCKeditor->BasePath	=  ASCMS_PATH_OFFSET.$FCKeditorBasePath;
			$objFCKeditor->Config['CustomConfigurationsPath'] = ASCMS_PATH_OFFSET.'/editor/FCKeditorConfig.php?langId='.$languageId.'&absoluteURIs='.$absoluteURIs;
			$objFCKeditor->Value = empty($value) ? '' : $value;

			if ($mode != 'html') {
				switch ($mode) {
				case 'shop':
					$objFCKeditor->Width = '100%';
					$objFCKeditor->Height = '200';
					break;

				case 'news':
					$objFCKeditor->Width = '100%';
					$objFCKeditor->Height = '350';
					$objFCKeditor->ToolbarSet = 'News';
					break;

				case 'teaser':
					$objFCKeditor->Width = '100%';
					$objFCKeditor->Height = '100';
					$objFCKeditor->ToolbarSet = 'News';
					break;

				case 'fullpage':
					$objFCKeditor->Width = '100%';
					$objFCKeditor->Height = '450';
					$objFCKeditor->Config['FullPage'] = true;
					break;

				default:
					$objFCKeditor->Width = '100%';
					$objFCKeditor->Height = '450';
					break;
				}
				$editor = $objFCKeditor->CreateHtml();
			} else {
			    $editor = '<textarea name="'.$name.'" style="width:100%; height:450px;">'.$value.'</textarea>';
			}
			return $editor;
			break;
	}
}
?>