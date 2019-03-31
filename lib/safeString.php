<?php
/**
* class security
*/
class safeString
{
	
	function __construct()
	{
		# code...
	}

//proteksi sql injection
	//basic *deprecated
	public function safeSql($string){
		return mysql_real_escape_string($string);
	}
	//safe sql param *deprecated
	public function safeSqlParam($string){
		return "'" . mysql_real_escape_string( $string ) . "'";
	}
//xss
	public function safeXss($string){

		$safestring=$this->alNum($string);
		$safestring=$this->safeHtmlEntiti($safestring);
		return $safestring;
	}

//proteksi tambahan
	//replace tabs, newlines, vertical tabs, formfeeds, carriage returns, spaces, and additionally,, buat antisipasi xss
	public function cleanSpaceString($string){
		return preg_replace("/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/","_",$string);
	}
	//html entiti
	public function safeHtmlEntiti($string){
		return htmlentities( $string, ENT_QUOTES, 'utf-8' );
	}
	//filter_var
	public function safeString($string){
		return filter_var ( $string, FILTER_SANITIZE_STRING);
	}
	//trim
	public function trimString($string){
		return trim($string);
	}
	//regex alphanumeric 
	public function alNum($string){
		return preg_replace('~[^\p{L}\p{N} !?@#$%*().,"[] ]++~u', '', $string);
	}
	//regex double space jadi single space
	public function singleSpace($string){
		return preg_replace('!\s+!', ' ', $string);
	}
	//over protect >> biasa buat username sama password,, bukan buat kek text area ato alamat etc
	public function overProtect($string){
		$safestring=$this->trimString($string);
		$safestring=$this->alNum($safestring);
		$safestring=$this->cleanSpaceString($safestring);
		$safestring=$this->safeString($safestring);
		//$safestring=$this->safeHtmlEntiti($safestring);

		return $safestring;
	}

	public function buat_keterangan($string){
		$safestring=$this->trimString($string);
		$safestring=$this->alNum($safestring);	
		$safestring=$this->safeString($safestring);		

		return $safestring;	
	}
	public function buat_nama($string){
		$safestring=$this->trimString($string);
		$safestring=$this->singleSpace($safestring);
		$safestring=$this->alNum($safestring);	
		$safestring=$this->safeString($safestring);		

		return $safestring;	
	}	

}

?>