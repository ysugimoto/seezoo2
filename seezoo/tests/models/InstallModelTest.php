<?php

require_once('bark.php');

class InstallModelTest extends SZ_KennelTest
{
	public function testCheckFilePermissions()
	{
		$arr = $this->model->checkFilePermissions();
		$this->assertContainsOnly('boolean', $arr);
	}
	
	public function testGetSystemRequiredFiles()
	{
		$ref = new ReflectionMethod($this->model, 'getSystemRequiredFiles');
		$ref->setAccessible(TRUE);
		$arr = $ref->invoke($this->model);
		$this->assertContainsOnly('string', $arr);
	}
	
	public function testCheckModRewrite()
	{
		$int = $this->model->checkModRewrite();
		$this->assertInternalType('int', $int);
		$this->assertGreaterThanOrEqual(0, $int);
		$this->assertLessThanOrEqual(2, $int);
	}
	
	public function testGetInstallURI()
	{
		$str = $this->model->getInstallURI();
		$this->assertInternalType('string', $str);
	}
	
	public function testGetInstallDirectory()
	{
		$str = $this->model->getInstallDirectory();
		$this->assertInternalType('string', $str);
	}
	
	public function testIsDatabaseEnable()
	{
		$dat = $this->getDBSettings();
		$bool = $this->model->isDatabaseEnable($dat);
		$this->assertTrue($bool);
	}
	
	public function getDBSettings()
	{
		include(APPPATH . 'config/database.php');
		$default = $database['default'];
		$dat = array(
			'db_address'  => $default['host'] . ':' . $default['port'],
			'db_username' => $default['username'],
			'db_password' => $default['password'],
			'db_name'     => $default['dbname']
		);
		return $dat;
	}
	
	public function testCreateSystemTable()
	{
		// ここテストできる？
		$this->assertTrue(TRUE);
	}
	
	public function registInstalledAdminUser()
	{
		// ここテストできる？
		$this->assertTrue(TRUE);
	}
	
	public function patchFiles()
	{
		// ここテストできる？
		$this->assertTrue(TRUE);
	}
	
	public function _patchDryRun()
	{
		$ref = new ReflectionMethod(get_class($this->model), '_patchDryRun');
		$ref->setAccessible(TRUE);
		$dat = $this->getDBSettings();
		foreach ( $this->model->getSystemRequiredFile() as $file)
		{
			$result = $ref->invoke($this->model, $file, $dat);
			$this->assertInternalType('string', $result);
		}
		
	}
}
