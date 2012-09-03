<?php

require_once('bark.php');

function db_datetime()
{
	return date('Y-m-d H:i:s');
}


class PageModelTest extends SZ_KennelTest
{
	public function testDetectPageOK()
	{
		$result = $this->model->detectPage('home');
		$this->assertInstanceOf('stdClass', $result);
	}
	
	public function testDetectPageNG()
	{
		$result = $this->model->detectPage('hogehoge');
		$this->assertFalse($result);
	}
	
	public function testGetPageObject()
	{
		$result = $this->model->getPageObject(1); // recent
		$this->assertInstanceOf('stdClass', $result);
		
		$result = $this->model->getPageObject(1, 'editting'); // edit
		$this->assertFalse($result);
		
		$result = $this->model->getPageObject(1, 'approve'); // approve
		$this->assertInstanceOf('stdClass', $result);
		
		$result = $this->model->getPageObject(1, 1); // version 1
		$this->assertInstanceOf('stdClass', $result);
		
		$result = $this->model->getPageObject(100, 'editting'); // enot found
		$this->assertFalse($result);
	}
	
	public function testGetPagePathByPageID()
	{
		$result = $this->model->getPagePathByPageID(1);
		$this->assertEquals('home', $result);
	}
	
	public function testGetFirstChildPage()
	{
		$result = $this->model->getFirstChildPage(1);
		$this->assertInstanceOf('stdClass', $result);
		
		$result = $this->model->getFirstChildPage(1000000000);
		$this->assertFalse($result);
	}
	
	public function testGetPageStatus()
	{
		$result = $this->model->getPageStatus(1);
		$this->assertInstanceOf('PagesActiveRecord', $result);
	}
}
