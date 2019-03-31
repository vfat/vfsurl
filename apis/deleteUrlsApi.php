<?php

/**
 * 
 */
class deleteUrlsApi extends mainApi
{
	
	public function delete($vf)
	{
		$log=new Log('request.log');
		$uid=new UUID;
		$token = $vf->get('PARAMS.token');
		$short_url = $vf->get('PARAMS.short_url');
		$writeUrl=new writeUrlsModel;
		$date = date('Y-m-d H:i:s');
		$problem = 0;
		$problem_msg = '';
		$msg='';

		try {
			$decode=$this->jwt->decode($token, $this->jwtkey, array('HS256'));
			$live=$uid->v5($decode->event_uid, $this->jwtkey);

			if($decode->live == $live)
			{
				$hasil=$writeUrl->deleteByShortUrl($short_url);
				$msg = json_encode(array('msg'=>'berhasil', 'content'=>$hasil, 'success'=>TRUE, 'token_status'=>'valid'));
			}
			else
			{
				$problem_msg = 'Perbarui Token';
				$msg = json_encode(array('msg'=>'gagal', 'content'=>'', 'success'=>FALSE, 'token_status'=>'valid', 'problem'=>$problem_msg));				
			}

			$log->write('[post-url-api][delete][success]'.$msg,'[Y-m-d H:i:s]');
			echo $msg;
		} catch (Exception $e) {
			$msg = json_encode(array('msg'=>'gagal', 'content'=>$e->getMessage(), 'success'=>FALSE, 'token_status'=>'invalid'));
			$log->write('[post-url-api][delete][failed]'.$msg,'[Y-m-d H:i:s]');
			echo $msg;
		}
	}

}

?>