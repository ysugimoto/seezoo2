<?php

require_once('bark.php');

class SitemapModelTest extends SZ_KennelTest
{
	public function testGetPageStructures()
	{
		$result = $this->model->getPageStructures();
		$this->assertInstanceOf('stdClass', $result);
	}
	
	public function testGetChildPageCount()
	{
		$ref = new ReflectionMethod($this->model, '_getChildPageCount');
		$ref->setAccessible(TRUE);
		$result = $ref->invoke($this->model, 1);
		$this->assertInternalType('int', $result);
	}
	
	public function testDetletePage()
	{
		// DB使うのでどうするか
	}
	
	public function testGetChildPages()
	{
		$result = $this->model->getChildPages(1);
		$this->assertInternalType('array', $result);
	}
	
	public function testMovePage()
	{
		// DB使うのでどうするか
	}
	
	public function testGetMaxDisplayOrder()
	{
		$result = $this->model->getMaxDisplayOrder(1);
		$this->assertInternalType('int', $result);
	}
	
	public function testGetDisplayPageLevel()
	{
		$ref = new ReflectionMethod($this->model, '_getDisplayPageLevel');
		$ref->setAccessible(TRUE);
		$result = $ref->invoke($this->model, 1, 1);
		$this->assertEquals(0, $result);
	}
	
	public function testGetCurrentVersion()
	{
		$result = $this->model->getCurrentVersion(1);
		$this->assertInternalType('int', $result);
	}
	
	public function testMergePagePath()
	{
		$ref = new ReflectionMethod($this->model, '_mergePagePath');
		$ref->setAccessible(TRUE);
		$result = $ref->invoke($this->model, 1, 2);
		$this->assertInternalType('string', $result);
	}
	
	public function testFixStrictChildPagePath()
	{
		// DB使うのでどうするか
	}
	
	public function testCopyPage()
	{
		// DB使うのでどうするか
	}
	
	public function testDuplicateArea()
	{
		// DB使うのでどうするか
	}
	
	public function testIsAliasExists()
	{
		$ref = new ReflectionMethod($this->model, '_isAliasExists');
		$ref->setAccessible(TRUE);
		$result = $ref->invoke($this->model, 1, 1);
		$this->assertFalse($result);
	}
	
	public function testIsPagePathExistsFalse()
	{
		$result = $this->model->isPagePathExists('hogehoge');
		$this->assertFalse($result);
	}
	
	public function testIsPagePathExistsTrue()
	{
		$result = $this->model->isPagePathExists('home');
		$this->assertTrue($result);
	}
	
	public function testRevertPageData()
	{
		// void
	}
	
	public function testMovePageOrderUpper()
	{
		$result = $this->model->movePageOrder(1, 2, 'upper');
		$this->assertFalse($result);
	}

	public function testMovePageOrderLower()
	{
		
	}
	
	public function testSortDisplayOrder()
	{
		
	}


}
