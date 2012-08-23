<?php

class InstallHelper
{
	public function setInstallIcons($name, $dir)
	{
		return '<img src="' . $dir . 'images/' . $name . '" alt="" />';
	}
}
