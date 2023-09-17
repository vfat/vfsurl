<?php

class Vf {

	static
		$vf;

	static function __callstatic($func,array $args) {
		if (!self::$vf)
			self::$vf=Base::instance();
		return call_user_func_array([self::$vf,$func],$args);
	}

}