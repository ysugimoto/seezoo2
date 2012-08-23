<?php

define('CILIBPATH', realpath(dirname(__FILE__) . '/libs') . '/');

require_once(CILIBPATH . 'Config.php');
require_once(CILIBPATH . 'Loader.php');
require_once(CILIBPATH . 'Input.php');
require_once(CILIBPATH . 'Output.php');
require_once(CILIBPATH . 'URI.php');

Seezoo::setAlias(array(
	'load'   => new CI_Loader,
	'config' => new CI_Config,
	'uri'    => new CI_URI,
	'input'  => new CI_Input,
	'output' => new CI_Output
));


