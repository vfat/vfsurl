<?php

/**
 * 
 */
class postUrlsApi extends mainApi
{
	
	public function add($vf)
	{
		$log=new Log('request.log');
		$short_url = $vf->get('POST.short_url');
		$origin_url = $vf->get('POST.origin_url');
		$event_uid = $vf->get('POST.event_uid');
		$token = $vf->get('PARAMS.token');
		$writeUrl=new writeUrlsModel;
		$date = date('Y-m-d H:i:s');
		$audit = new Audit;
		$problem = 0;
		$problem_msg = '';
		$msg='';

		try {
			$decode=$this->jwt->decode($token, $this->jwtkey, array('HS256'));
			if($event_uid==$decode->event_uid)
			{
				if(!$audit->url($origin_url))
				{
					$origin_url='http://'.$origin_url;
				}
				$hasil=$writeUrl->insert($short_url,$origin_url);
				if($hasil==1)
				{
					$msg = json_encode(array('msg'=>'berhasil', 'content'=>$hasil, 'success'=>TRUE, 'token_status'=>'valid'));
				}
				else
				{
					$problem_msg = 'Gagal Menyimpan';
					$msg = json_encode(array('msg'=>'gagal', 'content'=>$hasil, 'success'=>FALSE, 'token_status'=>'valid', 'problem'=>$problem_msg));
				}
				
			}
			else
			{
				$problem_msg = 'Anda Tidak di izinkan';
				$msg = json_encode(array('msg'=>'gagal', 'content'=>$e->getMessage(), 'success'=>FALSE, 'token_status'=>'invalid', 'problem'=>$problem_msg));
			}
			
			$log->write('[post-url-api][add][success]'.$msg,'[Y-m-d H:i:s]');
			echo $msg;
		} catch (Exception $e) {
			$msg = json_encode(array('msg'=>'gagal', 'content'=>$e->getMessage(), 'success'=>FALSE, 'token_status'=>'invalid'));
			$log->write('[post-url-api][add][failed]'.$msg,'[Y-m-d H:i:s]');
			echo $msg;
		}
	}

}

?>