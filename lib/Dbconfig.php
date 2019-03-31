<?php

/**
* 
*/
class Dbconfig
{
	
	protected $konek 	= "";

	public function __construct(){

	}	

	public function getKoneksi() {
		$vf=Core::instance();	
		try{
			
			$db = new PDO($vf->db_dns,$vf->db_user,$vf->db_pass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if($db===FALSE){

				throw new Exception("Koneksi Gagal");
				
			}else{

				$this->konek = $db;
			}
			
		}catch(Exception $e){

			echo "Error : ".$e->getMessage();
			$this->closeKoneksi();			
		}

		return $this->konek;
	}
	
	public function closeKoneksi(){
		
	$this->konek = NULL; //diskonek Koneksi
	}

	public function fetchObject($sql){

		$clone = array();
		
		try{

			$data =  $this->getKoneksi()->prepare($sql);
			$data->setFetchMode(PDO::FETCH_INTO,$this);
			$data->execute(); 

			/*fetch ke Object */
			while($result = $data->fetch()){
				
				$clone[] = clone $result;
			}

			$this->closeKoneksi();
			
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}

		return $clone;

	}

	public function fetchObjectbyData($sql,$array_execute){

		$clone = array();
		
		try{

			$data =  $this->getKoneksi()->prepare($sql);
			$data->setFetchMode(PDO::FETCH_INTO,$this);
			$data->execute($array_execute); 

			/*fetch ke Object */
			while($result = $data->fetch()){
				
				$clone[] = clone $result;
			}

			$this->closeKoneksi();
			
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}

		return $clone;

	}

	public function fetchASSOC($sql){
		try{

			$data =  $this->getKoneksi()->prepare($sql);

			$data->execute(); 

			$result = $data->fetchAll(PDO::FETCH_ASSOC);

			$this->closeKoneksi();
			
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}

		return $result;
	}

	public function eksekusi($sql,$array_execute){
		$response=FALSE;
		try{

			$data =  $this->getKoneksi()->prepare($sql);

			$data->execute($array_execute); 
			$response=TRUE;
			$this->closeKoneksi();
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}  
		return $response; 		
	}

	public function exekusi($sql,$array_execute,$sqlLastId){
		$id=0;
		//$response=FALSE;
		try{
			$id = $this->getKoneksi()->query($sqlLastId)->fetch(PDO::FETCH_ASSOC)['Auto_increment'];
			$data =  $this->getKoneksi()->prepare($sql);

			$data->execute($array_execute); 
			
			//$response=TRUE;
			$this->closeKoneksi();
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}  
		return $id; 		
	}

	public function numRow($sql){
		try{

			$data =  $this->getKoneksi()->prepare($sql);

			$data->execute(); 
			$result = $data->fetchColumn();

			$this->closeKoneksi();
			
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}

		return $result;
	}
	public function numRowbyData($sql,$array_execute){
		try{

			$data =  $this->getKoneksi()->prepare($sql);

			$data->execute($array_execute); 
			$result = $data->fetchColumn();

			$this->closeKoneksi();
			
		}catch(PDOException $e){
			$this->closeKoneksi();
			echo $e->getMessage();
		}

		return $result;
	}

//tambahan
	static function limit ( $request )
	{
		$limit = '';
		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
		}
		$this->closeKoneksi();
		return $limit;
	}	

	public function last_id($sql){
		try {
			$id = $this->getKoneksi()->query($sql)->fetch(PDO::FETCH_ASSOC)['Auto_increment'];
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		$this->closeKoneksi();
		return $id;
	}


}

?>