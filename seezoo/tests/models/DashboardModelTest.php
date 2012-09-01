<?php

require_once('bark.php');

class DashboardModelTest extends SZ_KennelTest
{
	public function testRegistAdminUser()
	{
		
	}
	
	public function testEncryptPassword()
	{
		$result = $this->model->encryptPassword('111111');
		$this->assertInternalType('array', $result);
	}
	
	public function testStretchPassword()
	{
		$hash     = sha1(uniqid(mt_rand(), TRUE));
		$result = $this->model->stretchPassword($hash, '111111');
		$this->assertInternalType('string', $result);
	}
	
	public function testGetEditPageCount()
	{
		$result = $this->model->getEditPageCount(1);
		$this->assertEquals($result, 0);
	}
	
	public function testGetApproveStatuses()
	{
		$result = $this->model->getApproveStatuses(1);
		$this->assertInternalType('array', $result);
	}
	
	public function testGetApproveRequests()
	{
		$result = $this->model->getApproveRequests(1, TRUE);
		$this->assertInternalType('array', $result);
	}
	
	
	
}
