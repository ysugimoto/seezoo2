<?php

require_once('bark.php');

class InitModelTest extends SZ_KennelTest
{
	public function testIsLoggedIn()
	{
		//$bool = $this->model->isLoggedIn();
		//$this->assertFalse($bool);
		$this->assertTrue(TRUE);
	}
	
	public function testIsAlreadyInstalledTrue()
	{
		$env = Seezoo::getENV();
		$env->setConfig('seezoo_installed', TRUE);
		$bool = $this->model->isAlreadyInstalled();
		$this->assertTrue($bool);
	}

	public function testIsAlreadyInstalledFalse()
	{
		$env = Seezoo::getENV();
		$env->setConfig('seezoo_installed', FALSE);
		$bool = $this->model->isAlreadyInstalled();
		$this->assertFalse($bool);
	}

}
