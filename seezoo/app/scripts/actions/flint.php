<?php  if ( ! defined('SZ_EXEC')) exit('No direct script access allowed');

$Request = Seezoo::getRequest();
$Session = Seezoo::$Importer->library('Session');
$Init    = Seezoo::$Importer->model('InitModel');

require_once(APPPATH . 'data/flint.config.php');
$setting = $config['init_config'];
$setting['siteUrl'] = page_link() . 'index.php';
$setting['isModRewrite'] = get_config('enable_mod_rewirte');

if ( $Session->get('sz_token') )
{
	$setting['sz_token'] = $Session->get('sz_token');
}
else
{
	$token = sha1(microtime());
	$setting['sz_token'] = $token;
	$Session->set('sz_token', $token);
}
$setting['is_login'] = $Init->isLoggedIn();
$mode   = $Request->get('mode');
$userID = $Session->get('user_id');
if ( ! ctype_digit($mode) )
{
	$setting['routingMode'] = $mode;
}
else
{
	$PageModel  = Seezoo::$Importer->model('PageModel');
	$versinMode = ( $setting['is_login'] ) ? 'recent' : 'approve';
	$status     = $PageModel->getPageStatus($mode);
	
	$setting['version_number'] = $Request->get('v');
	$setting['can_edit'] = ( strpos($status->allow_edit_user, ':' . $userID . ':') !== FALSE ) ? TRUE : FALSE;
	$setting['is_edit']  = ( $status->edit_user_id == $userID ) ? TRUE : FALSE;
}

if ( $setting['directoryList'] === 'auto' )
{
	$setting['directoryList'] = getControllers('./' . $setting['scriptPath'] . 'controllers');
}

Seezoo::$Response->setHeader('Content-Type', 'text/javascript');
Seezoo::$Response->display(buildConfig($setting));


// inline function
function getControllers($path)
{
	$ret = array();
	$dir = opendir($path);

	if ( $dir )
	{
		while ( FALSE !== ($f = readdir($dir)) )
		{
			if ( is_dir($path . '/' . $f) && ! preg_match('/^[\.]/', $f) )
			{
				$ret[] = $f;
				$dir2 = opendir($path . '/' . $f);

				if ( $dir2 )
				{
					while ( FALSE !== ($f2 = readdir($dir2)) )
					{
						if ( is_dir($path . '/' . $f . '/' . $f2) && ! preg_match('/^[\.]/', $f2) )
						{
							$ret[] = $f . '/' . $f2;
						}
					}
					closedir($dir2);
				}
			}
		}
		closedir($dir);
	}
	return $ret; // config array
}

function buildConfig($setting)
{
	$jsonString = 'FL_CONFIG = ' . json_encode($setting);
	return preg_replace('/[\r|\n|\r\n|\t|\s]/', '', $jsonString);
}
