<?php
/**
 * Ajex.FileManager
 * http://demphest.ru/ajex-filemanager
 *
 * @version
 * 1.0.4
 *
 * @copyright
 * Copyright (C) 2009-2010 Demphest Gorphek
 *
 * @license
 * Dual licensed under the MIT and GPL licenses.
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Ajex.FileManager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This file is part of Ajex.FileManager.
 */

header('Expires: Sun, 13 Sep 2009 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache') ;
//header('Content-Type: text/json; charset=utf-8');

define('DEV', false);
if (DEV) {
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	ini_set('display_startup_errors', 'on');
} else {
	error_reporting(0);
	ini_set('display_errors', 'off');
	ini_set('display_startup_errors', 'off');
}

//if (!isset($_SESSION['admin'])) {exit;}			// Do not forget to add your user authorization


define('DIR_SEP', '/');
mb_internal_encoding('utf-8');
date_default_timezone_set('Europe/Moscow');

$cfg['url']	= 'upload';
$cfg['root']	= $_SERVER['DOCUMENT_ROOT'] . DIR_SEP . $cfg['url'];			// http://www.yousite.com/upload/		absolute path
$cfg['quickdir'] = 'quickdir';		//$cfg['quickdir'] = 'quick-folder';		// for CKEditor

$cfg['lang']	= 'ru';

$cfg['thumb']['width'] 	= 200;
$cfg['thumb']['height']	= 150;
$cfg['thumb']['quality']	= 90;
$cfg['thumb']['cut']		= true;
$cfg['thumb']['auto']	= true;
$cfg['thumb']['dir']		= '_thumb';
$cfg['thumb']['date']	= "j.m.Y, H:i";

$cfg['hide']['file']	= array('.htaccess');
$cfg['hide']['folder']	= array('.', '..', $cfg['thumb']['dir'], '.svn', '.cvs');

$cfg['chmod']['file']		= 0777;
$cfg['chmod']['folder']	= 0777;

$cfg['deny'] = array(
	'file'		=> array('php','php3','php4','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi'),
	'image'	=> array(),

	'folder'	=> array(
			$cfg['url'] . DIR_SEP . 'file',
			$cfg['url'] . DIR_SEP . 'image')
);

$cfg['allow'] = array(
	'file'	=> array('7z', 'doc', 'docx','flv',  'gz', 'gzip', 'gif', 'bmp', 'jpeg', 'jpg', 'png','mid', 'mov', 'mp3', 'mp4', 'mpeg', 'mpg', 'pdf',  'rar', 'swf', 'tar', 'tgz', 'txt', 'xls','xlsx','xml','zip'),
    'image'	=> array('7z', 'doc', 'docx','flv',  'gz', 'gzip', 'gif', 'bmp', 'jpeg', 'jpg', 'png','mid', 'mov', 'mp3', 'mp4', 'mpeg', 'mpg', 'pdf',  'rar', 'swf', 'tar', 'tgz', 'txt', 'xls','xlsx','xml','zip')
	);


$cfg['nameRegAllow'] = '/^[a-z0-9-_#~\$%()\[\]&=]+/i';

//	------------------
$cfg['url']	= trim($cfg['url'], '/\\');
$cfg['root']	= rtrim($cfg['root'], '/\\') . DIR_SEP;

$dir = isset($_POST['dir'])? urldecode($_POST['dir']) : '';
$dir = trim($dir, '/\\') . DIR_SEP;

$rpath = str_replace('\\', DIR_SEP, realpath($cfg['root'] . $dir) . DIR_SEP);
if (false === strpos($rpath, str_replace('\\', DIR_SEP, $dir))) {$dir = '';}


$mode = isset($_GET['mode'])? $_GET['mode'] : 'getDirs';
$cfg['type']	= isset($_POST['type'])? $_POST['type'] : (isset($_GET['type']) && 'QuickUpload' == $mode? $_GET['type'] : 'file');
$cfg['sort']	= isset($_POST['sort'])? $_POST['sort'] : 'name';

$cfg['type'] = strtolower($cfg['type']);

$reply = array(
	'dirs'		=> array(),
	'files'		=> array()
);

//	------------------

require_once 'lib.php';

switch($mode) {
	case 'cfg':
		$rootDir = listDirs('');
		$children = array();
		for ($i=-1, $iCount=count($rootDir); ++$i<$iCount;) {
			$children[] = (object) $rootDir[$i];
		}

		$reply['config'] = array(
			'lang'		=> $cfg['lang'],
			'type'		=> $cfg['type'],
			'url'		=> '/' . $cfg['url'] . '/',
			'thumb'	=> $cfg['thumb']['dir'],
			'thumbWidth'	=> $cfg['thumb']['width'],
			'thumbHeight'	=> $cfg['thumb']['height'],
			'maxUpload' => ini_get('upload_max_filesize'),
			'allow'		=> implode('|', $cfg['allow'][$cfg['type']]),
			'children'	=> $children
		);
		break;

	case 'renameFile':
		$file = trim(urldecode($_POST['oldname']), '/\\.');
		$name = urldecode($_POST['newname']);

		if ($file != $name && preg_match($cfg['nameRegAllow'], $name) && file_exists($cfg['root']) . $dir . $file) {
			if (file_exists($_thumb = $cfg['root'] . $cfg['thumb']['dir'] . DIR_SEP . $dir . DIR_SEP . $file)) {
				unlink($_thumb);
			}
			if (file_exists($cfg['root'] . $dir . $name)) {
				$name = getFreeFileName($name, $cfg['root'] . $dir);
			}
			if (false !== strpos($name, '.')) {
				$ext = substr($name, strrpos($name, '.') + 1);
				if (in_array($ext, $cfg['allow']['image'])) {
					rename($cfg['root'] . $dir . $file, $cfg['root'] . $dir . $name);
				}
			}
		}

		$reply['files'] = listFiles($dir);
		break;

	case 'createFolder':
		$path = trim(urldecode($_POST['oldname']), '/\\.');
		$name = urldecode($_POST['newname']);

		$reply['isSuccess'] = false;
		if (preg_match($cfg['nameRegAllow'], $name)) {
			if (!file_exists($cfg['root'] . $path . DIR_SEP . $name)) {
				$reply['isSuccess'] = mkdir($cfg['root'] . $path . DIR_SEP . $name, $cfg['chmod']['folder']);
			} else {
				$reply['isSuccess'] = 'exist';
			}
		}
		break;

	case 'renameFolder':
		$folder	= urldecode($_POST['oldname']);
		$name	= urldecode($_POST['newname']);
		$folder	= trim($folder, '/\\.');

		$reply['isSuccess'] = false;
		if (!empty($folder) && $cfg['url'] != $folder && $folder != $name && !in_array($cfg['url'] . DIR_SEP . $folder, $cfg['deny']['folder']) && preg_match($cfg['nameRegAllow'], $name) && is_dir($cfg['root']) . $folder) {
			$reply['isSuccess'] = rename($cfg['root'] . $folder, $cfg['root'] . substr($folder, 0, strrpos($folder, '/')) . DIR_SEP . $name);
		}
		break;

	case 'deleteFolder':
		$reply['isDelete'] = false;
		$folder = trim($dir, '/\\');

		if (!empty($folder) && $cfg['url'] != $folder && !in_array($cfg['url'] . DIR_SEP . $folder, $cfg['deny']['folder'])) {
			deleteDir($cfg['root'] . $cfg['thumb']['dir'] . DIR_SEP. $folder);
			$reply['isDelete'] = deleteDir($cfg['root'] . $folder);
		}
		break;

	case 'uploads':
		$reply['downloaded'] = array();
		$width	= isset($_POST['resizeWidth'])? intval($_POST['resizeWidth']) : 0;
		$height	= isset($_POST['resizeHeight'])? intval($_POST['resizeHeight']): 0;

		$key = 'uploadFiles';
		if (!empty($dir) && '/' != $dir && !empty($_FILES[$key])) {
			$aImg = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
			for ($i=-1, $iCount=count($_FILES[$key]['name']); ++$i<$iCount;) {
				$ext = strtolower(substr($_FILES[$key]['name'][$i], strrpos($_FILES[$key]['name'][$i], '.') + 1));
				 
			
				if (!in_array($ext, $cfg['deny'][$cfg['type']]) && in_array($ext, $cfg['allow'][$cfg['type']])) {
					
					$freeName = getFreeFileName($_FILES[$key]['name'][$i], $cfg['root'] . $dir);
					if(in_array($ext,$aImg)){
						if ($width || $height) {
							create_thumbnail($_FILES[$key]['tmp_name'][$i], $cfg['root'] . $dir . $freeName, $width, $height, 100, false, true);
							chmod($cfg['root'] . $dir . $freeName, $cfg['chmod']['file']);
							$reply['downloaded'][] = array(true, $freeName);
						} else {
							if (move_uploaded_file($_FILES[$key]['tmp_name'][$i], $cfg['root'] . $dir . $freeName)) {
								chmod($cfg['root'] . $dir . $freeName, $cfg['chmod']['file']);
								if ($cfg['thumb']['auto']) {
									create_thumbnail($cfg['root'] . $dir . $freeName, $cfg['root'] . $cfg['thumb']['dir'] . DIR_SEP . $dir . DIR_SEP. $freeName);
									chmod($cfg['root'] . $cfg['thumb']['dir'] . DIR_SEP . $dir . DIR_SEP. $freeName, $cfg['chmod']['file']);
								}
								$reply['downloaded'][] = array(true, $freeName);
							} else {
								$reply['downloaded'][] = array(false, $freeName);
							}
						}
					} else {
						if (move_uploaded_file($_FILES[$key]['tmp_name'][$i], $cfg['root'] . $dir . $freeName)) {
							chmod($cfg['root'] . $dir . $freeName, $cfg['chmod']['file']);
							$reply['downloaded'][] = array(true, $freeName);
						} else {
							$reply['downloaded'][] = array(false, $freeName);
						}
					}
				} else {
					$reply['downloaded'][] = array(false, $_FILES[$key]['name'][$i]);
				}
			}
		}
		break;
		
		
		case 'cropImage':
		//echo '<pre>'; print_r($_POST); echo '</pre>';
		//if($_POST['resize'])
		$dir_upload = $cfg['root'].$_POST['dir'].'/';
		$filename = $_POST['files'];
		
		
		$freeName = cropImg($dir_upload,$filename,$_POST['cw'],$_POST['ch']);
		create_thumbnail($dir_upload.$filename, $cfg['root'] . $cfg['thumb']['dir'] . DIR_SEP . $_POST['dir'] . DIR_SEP. $filename);
		$reply['crop'] = $freeName;
		
		
		
		
		//create_thumbnail($cfg['root']$_POST[$key]['tmp_name'][$i], $cfg['root'] . $dir . $freeName, $width, $height, 100, false, true);
		break;
		
		
		case 'resizeImage':
		//echo '<pre>'; print_r($_POST); echo '</pre>';
		
		
		
		
		//create_thumbnail($cfg['root']$_POST[$key]['tmp_name'][$i], $cfg['root'] . $dir . $freeName, $width, $height, 100, false, true);
		break;
		
		
		
		

	case 'QuickUpload':
		switch ($cfg['type']) {
			case 'file':
			    $dir = $cfg['type'];
			case 'image':
				$dir = $cfg['type'];
				break;
			default:
				exit;		//	exit	for not supported type
				break;
		}
		if (!is_dir($toDir = $cfg['root'] . $dir . DIR_SEP . $cfg['quickdir'])) {
			mkdirs($toDir, $cfg['chmod']['folder']);
		}

		if (0 == ($_FILES['upload']['error'])) {
			$fileName = getFreeFileName($_FILES['upload']['name'], $toDir);
			$ext = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	        $aImg = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
			if(in_array($ext,$aImg)){
			    list($width, $height) = @getimagesize($_FILES['upload']['tmp_name']);
				$widthimg = ($width > 800) ? 800 : $width;
				
			create_thumbnail($_FILES['upload']['tmp_name'],$toDir . DIR_SEP . $fileName, $widthimg, '', 90, false, true);
			if (!in_array($ext, $cfg['deny'][$cfg['type']]) && in_array($ext, $cfg['allow'][$cfg['type']]) ) {
				$funcNum = isset($_GET['CKEditorFuncNum'])? intval($_GET['CKEditorFuncNum']) : 2;
				$result = "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $funcNum . ", '/". $cfg['url'] . '/' . $dir . '/' . (empty($cfg['quickdir'])? '' : trim($cfg['quickdir'], '/\\') . '/') . $fileName."', '');</script>";
			}
			} else {
				if (move_uploaded_file($_FILES['upload']['tmp_name'], $toDir . DIR_SEP . $fileName)) {
					$funcNum = isset($_GET['CKEditorFuncNum'])? intval($_GET['CKEditorFuncNum']) : 2;
				$result = "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $funcNum . ", '/". $cfg['url'] . '/' . $dir . '/' . (empty($cfg['quickdir'])? '' : trim($cfg['quickdir'], '/\\') . '/') . $fileName."', '');</script>";
				}
				
			}
		}

		exit($result);
		break;

	case 'deleteFiles':
		$files = urldecode($_POST['files']);
		$files = explode('::', $files);
		for ($i=-1, $iCount=count($files); ++$i<$iCount;) {
			unlink($cfg['root'] . $dir . $files[$i]);
			file_exists($_thumb = $cfg['root'] . $cfg['thumb']['dir']  . DIR_SEP. $dir . DIR_SEP . $files[$i])? unlink($_thumb): null;
		}

		$reply['files'] = listFiles($dir);
		break;

	case 'getFiles':
		$reply['files'] = listFiles($dir);
		break;

	case 'getDirs':
		$reply['dirs'] = listDirs($dir);
		break;

	default:
		exit;
		break;
}

if (isset($_GET['noJson'])) {echo'<pre>';print_r($reply);echo'</pre>';exit;}
exit( json_encode( $reply ) );