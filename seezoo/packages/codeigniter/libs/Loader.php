<?php

class CI_Loader
{
	protected $load;
	
	public function __construct()
	{
		$this->load = Seezoo::$Importer->classes('Importer');
	}
	
	public function library($libName)
	{
		$this->load->library($libName);
	}
	
	public function model($modelName)
	{
		$this->load->model($modelName);
	}
	
	public function helper($helperName)
	{
		return $this->load->helper($helperName);
	}
	
	public function view($path, $vars = array(), $return = FALSE)
	{
		$SZ = Seezoo::getInstance();
		return $SZ->view->render($path, $vars, $return);
	}
	
	public function config($conf, $orig = FALSE)
	{
		$this->load->config($conf, $orig);
	}
}
