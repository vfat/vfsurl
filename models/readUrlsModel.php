<?php

/**
 * 
 */
class readUrlsModel extends mainModel
{
	
	public function get()
	{
		$db=$this->db;
		$sql="SELECT * FROM urls LIMIT 0,10";

		try {
			$hasil = $db->exec($sql);
			return $hasil;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	function getByShortUrl($short_url)
	{
		$db=$this->db;
		$sql="SELECT * FROM urls WHERE short_url=:short_url";
		$data[':short_url']=$short_url;

		try {
			$hasil = $db->exec($sql,$data);
			return $hasil;
		} catch (Exception $e) {
			return $e->getMessage();
		}		
	}

}

?>