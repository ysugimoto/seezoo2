<?php

require_once('bark.php');

class AuthModelTest extends SZ_KennelTest
{
	public function testCheckRememberLoggedIn()
	{
		$result = $this->model->checkRememberLoggedIn();
		$this->assertFalse($result);
	}
	
	public function testAdminLoginOK()
	{
		$result = $this->model->adminLogin('admin', '111111', 0);
		$this->assertInternalType('string', $result);
	}
	
	public function testAdminLoginNG()
	{
		$result = $this->model->adminLogin('', '', 0);
		$this->assertFalse($result);
	}
	
	public function testIsEmailExistsOK()
	{
		$result = $this->model->isEmailExists('neo.yoshiaki.sugimoto@gmail.com');
		$this->assertTrue($result);
	}
	
	public function testIsEmailExistsNG()
	{
		$result = $this->model->isEmailExists('test@example.com');
		$this->assertFalse($result);
	}
}
