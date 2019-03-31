<?php

/**
 * 
 */
class writeUrlsModel extends mainModel
{
	
	public function insert($short_url, $origin_url)
	{
		$db=$this->db;
		$sql="INSERT INTO `urls` (`short_url` , `origin_url`, `hits`, `status`) VALUES (:short_url, :origin_url, :hits, :status)";
		$data[':short_url']=$short_url;
		$data[':origin_url']=$origin_url;
		$data[':hits']=0;
		$data[':status']=1;

		try {
			$hasil = $db->exec($sql,$data);
			return $hasil;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	function update($short_url=NULL, $origin_url=NULL, $hits=NULL, $status=NULL)
	{
		$db=$this->db;
		$i=0;
		$target = NULL;

		if(!is_null($origin_url))
		{
			if($i==0)
			{
				$target .= " origin_url='".$origin_url."'";
			}
			else
			{
				$target .= ", origin_url='".$origin_url."'";
			}

			$i++;
		}

		if(!is_null($hits))
		{
			if($i==0)
			{
				$target .= "hits=hits+1";
			}
			else
			{
				$target .= ", hits=hits+1";
			}

			$i++;
		}

		if(!is_null($origin_url))
		{
			if($i>0)
			{
				$target .= "status='".$status."'";
			}
			else
			{
				$target .= ", status='".$status."'";
			}

			$i++;
		}		

		$sql = "UPDATE `urls` SET $target  WHERE `short_url`=:short_url";
		$data[':short_url'] = $short_url;


		try {
			$hasil = $db->exec($sql,$data);
			return $hasil;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	function deleteByShortUrl($short_url=NULL)
	{
		$db=$this->db;

		$sql = "DELETE FROM `urls` WHERE `short_url`=:short_url";
		$data[':short_url'] = $short_url;

		try {
			$hasil = $db->exec($sql,$data);
			return $hasil;
		} catch (Exception $e) {
			return $e->getMessage();
		}		
	}

}

?>