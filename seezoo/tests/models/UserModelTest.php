<?php

require_once('bark.php');

class UserModelTest extends SZ_KennelTest
{
	public function testIsAdminOK()
	{
		$result = $this->model->isAdmin(1);
		$this->assertTrue($result);
	}
	
	public function testIsAdminNG()
	{
		$result = $this->model->isAdmin(100);
		$this->assertFalse($result);
	}
	
	public function testGetUserList()
	{
		$result = $this->model->getUserList();
		$this->assertInternalType('array', $result);
	}

}
