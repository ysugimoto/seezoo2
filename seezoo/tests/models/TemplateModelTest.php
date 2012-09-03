<?php

require_once('bark.php');

class TemplateModelTest extends SZ_KennelTest
{
	public function testGetTemplateList()
	{
		$result = $this->model->getTemplateList();
		$this->assertInternalType('array', $result);
		$this->assertContainsOnly('object', $result);
	}
}
