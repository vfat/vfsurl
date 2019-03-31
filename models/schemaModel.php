<?php

/**
 * 
 */
class schemaModel extends mainModel
{
	
	public function get()
	{
		$db=$this->db;
		$sql="SELECT name FROM sqlite_master WHERE type=:type";
		$data[':type']='table';

		try {
			$hasil = $db->exec($sql,$data);
			return $hasil;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

}

?>