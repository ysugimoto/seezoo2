<?php 
$route = $controller->set_routing();

if ( $route === 'init' )
{
	echo $this->load->block_view('form/parts/input');
}
else if ( $route === 'confirm' )
{
	echo $this->load->block_view('form/parts/confirm');
}
else if ( $route === 'send' )
{
	echo $this->load->block_view('form/parts/send');
}
