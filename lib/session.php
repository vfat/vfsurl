<?php

//! Cache-based session handler
class Session {

	protected
		//! Session ID
		$sid,
		//! Anti-CSRF token
		$_csrf,
		//! User agent
		$_agent,
		//! IP,
		$_ip,
		//! Suspect callback
		$onsuspect,
		//! Cache instance
		$_cache;

	//Open session
	function open($path,$name) {
		return TRUE;
	}

	//Close session
	function close() {
		$this->sid=NULL;
		return TRUE;
	}

	//read session by id
	function read($id) {
		$this->sid=$id;
		if (!$data=$this->_cache->get($id.'.@'))
			return '';
		if ($data['ip']!=$this->_ip || $data['agent']!=$this->_agent) {
			$vf=Core::instance();
			if (!isset($this->onsuspect) ||
				$vf->call($this->onsuspect,[$this,$id])===FALSE) {
				$this->destroy($id);
				$this->close();
				unset($vf->{'COOKIE.'.session_name()});
				$vf->error(403);
			}
		}
		return $data['data'];
	}

	//Write session
	function write($id,$data) {
		$vf=Core::instance();
		$jar=$vf->JAR;
		$this->_cache->set($id.'.@',
			[
				'data'=>$data,
				'ip'=>$this->_ip,
				'agent'=>$this->_agent,
				'stamp'=>time()
			],
			$jar['expire']?($jar['expire']-time()):0
		);
		return TRUE;
	}

	//Destroy session
	function destroy($id) {
		$this->_cache->clear($id.'.@');
		return TRUE;
	}

	//reset session
	function cleanup($max) {
		$this->_cache->reset('.@',$max);
		return TRUE;
	}

	//session id
	function sid() {
		return $this->sid;
	}

	//anti-CSRF token
	function csrf() {
		return $this->_csrf;
	}

	//IP address
	function ip() {
		return $this->_ip;
	}

	//Unix timestamp
	function stamp() {
		if (!$this->sid)
			session_start();
		return $this->_cache->exists($this->sid.'.@',$data)?
			$data['stamp']:FALSE;
	}

	//Return HTTP user agent
	function agent() {
		return $this->_agent;
	}

	//Instantiate class
	function __construct($onsuspect=NULL,$key=NULL,$cache=null) {
		$this->onsuspect=$onsuspect;
		$this->_cache=$cache?:Cache::instance();
		session_set_save_handler(
			[$this,'open'],
			[$this,'close'],
			[$this,'read'],
			[$this,'write'],
			[$this,'destroy'],
			[$this,'cleanup']
		);
		register_shutdown_function('session_commit');
		$vf=\Core::instance();
		$headers=$vf->HEADERS;
		$this->_csrf=$vf->SEED.'.'.$vf->hash(mt_rand());
		if ($key)
			$vf->$key=$this->_csrf;
		$this->_agent=isset($headers['User-Agent'])?$headers['User-Agent']:'';
		$this->_ip=$vf->IP;
	}

}
