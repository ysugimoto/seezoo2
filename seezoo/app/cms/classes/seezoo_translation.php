<?php
/**
 * do translate function
 * @return string $str
 */
function sz_translate($str)
{
	include(APPPATH . 'config/translations.php');

	return str_replace($original_messages, $translates, $str);
}