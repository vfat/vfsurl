<?php

/*
================
API UTAMA
================
*/
class mainApi
{

	protected $vf;
	protected $ss;protected $jwt;protected $jwtkey;

	function __construct()
	{
		$vf=Base::instance();

		$this->vf=$vf;
		$ss=new safeString;$jwt=new JWT;$jwtkey = date('dmY');

		$this->jwt=$jwt;
		$this->jwtkey=$jwtkey;
		$this->ss=$ss;
	}
}