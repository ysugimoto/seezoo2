<?php

require_once('bark.php');

class UserModelTest extends SZ_KennelTest
{
	public function testIsAdminOK()
	{
		$result = $this->model->isAdmin(1);
		$this->assertTrue($result);
	}
	
	public function testIsAdminZero()
	{
		$result = $this->model->isAdmin(0);
		$this->assertFalse($result);
	}
	
	public function testIsAdminNG()
	{
		$result = $this->model->isAdmin(100);
		$this->assertFalse($result);
	}

}
