<?php

/**
 * 
 */
class getUrlsApi extends mainApi
{
	
	public function get($vf)
	{
		$log=new Log('request.log');
		$uid=new UUID;
		$token = $vf->get('PARAMS.token');
		$readUrl=new readUrlsModel;
		$date = date('Y-m-d H:i:s');
		$problem = 0;
		$problem_msg = '';
		$msg='';

		try {
			$decode=$this->jwt->decode($token, $this->jwtkey, array('HS256'));
			$live=$uid->v5($decode->event_uid, $this->jwtkey);

			if($decode->live == $live)
			{
				$hasil=$readUrl->get();
				$msg = json_encode(array('msg'=>'berhasil', 'content'=>$hasil, 'success'=>TRUE, 'token_status'=>'valid'));
			}
			else
			{
				$problem_msg = 'Perbarui Token';
				$msg = json_encode(array('msg'=>'gagal', 'content'=>'', 'success'=>FALSE, 'token_status'=>'valid', 'problem'=>$problem_msg));				
			}

			$log->write('[post-url-api][get][success]'.$msg,'[Y-m-d H:i:s]');
			echo $msg;
		} catch (Exception $e) {
			$msg = json_encode(array('msg'=>'gagal', 'content'=>$e->getMessage(), 'success'=>FALSE, 'token_status'=>'invalid'));
			$log->write('[post-url-api][get][failed]'.$msg,'[Y-m-d H:i:s]');
			echo $msg;
		}
	}

}

?>