<?php
/**
 * 
 */
class homeController extends mainController
{
	
	function index($vf)
	{
		try {
			$view=$this->view;
			$schemaModel=new schemaModel;
			$uid=new UUID;
			$jwt=$this->jwt;
			$ss=$this->ss;
			$event_uid=$uid->v4();
			$live=$uid->v5($event_uid, $this->jwtkey);		
			$payload=array(
				'iss' => $_SERVER['HTTP_USER_AGENT'],
				'aud' => $_SERVER['HTTP_HOST'],
				'event_uid' => $event_uid,
				'live' => $live		
				);
			$token=$jwt->encode($payload, $this->jwtkey);

			$vf->set('nav','home');
			$vf->set('content','home/index');
			$vf->set('token',$token);
			$vf->set('event_uid',$event_uid);
			$vf->set('host',$_SERVER['HTTP_HOST']);


			echo $view->render('v1/layout.php');	
		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}

	function proses_url($vf)
	{
		$readUrl=new readUrlsModel;
		$writeUrl=new writeUrlsModel;
		try {
			$url = $vf->get('PARAMS.url');

			$hasil = $readUrl->getByShortUrl($url);

			$origin_url = '/';
			if($hasil){
				foreach ($hasil as $row) {
					$origin_url = $row['origin_url'];
				}
				$writeUrl->update($url,NULL,1,NULL);
				$vf->reroute($origin_url);
			}
			else
			{
				$vf->reroute($origin_url);
			}

		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
	}

	function tes_uuid($vf)
	{
		$uid=new UUID;
		$uid1=$uid->v4();
		echo $uid1.'<br>';
		$uid2=$uid->v5($uid1,'sssss');
		echo $uid2.'<br>';
	}


}


?>