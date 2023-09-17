<?php

/*
================
MODEL UTAMA
================
*/
class mainModel
{

	protected $vf;
	protected $view;
	protected $db;

	function __construct()
	{
		$vf=Base::instance();
		$view=new View;

		$this->vf=$vf;
		$this->view=$view;

		// konek db
		$db=new DB\SQL($vf->get('db'));
		
		$this->db=$db;

	}
}

?>