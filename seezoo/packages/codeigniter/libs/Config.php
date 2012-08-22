<?php

class CI_Config
{
	protected $env;
	
	public function __construct()
	{
		$this->env = Seezoo::getENV();
	}
	public function item($name)
	{
		return $this->env->getConfig($name);
	}
	public function set_item($name, $val)
	{
		$this->env->setConfig($name, $val);
	}
	
	public function slash_item($name)
	{
		return trail_slash((string)$this->env->getConfig($name));
	}
}