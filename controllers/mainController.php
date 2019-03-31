<?php

/*
================
CONTROLLER UTAMA
================
*/
class mainController
{

	protected $vf;
	protected $view;
	protected $ss;protected $jwt;protected $jwtkey;

	function __construct()
	{
		$vf=Core::instance();
		$view=new View;

		$this->vf=$vf;
		$this->view=$view;

		// konek db
		$db=new DB\SQL($vf->get('db'));
		if (file_exists('create_db.sql')) {
			// init
			$db->exec(explode(';',$vf->read('create_db.sql')));
			// rename
			rename('create_db.sql','create_db.$ql');
		}
		// session
		//new DB\SQL\Session($db);

		$ss=new safeString;$jwt=new JWT;$jwtkey = date('dmY');

		$this->jwt=$jwt;
		$this->jwtkey=$jwtkey;
		$this->ss=$ss;

	}
}

?>