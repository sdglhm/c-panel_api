<?php 
	// cPanel/WHM api from eezpal
	// Author: eezpal
	// Developer: Lahiru Himesh Madusanka
	// URL: http://eezpal.com
	// This api will provide basic functions that requires to use cPanel/whm api using PHP. 

	namespace eezpal\cPanel_api;

	class cPanel
	{

		private $host;
		private $username;
		private $hash;
		protected $headers=array();

		public function __construct($options=array()){
			return $this->checkSettings($options)
				->setHost($options['host'])
				->setAuth($options['user'], $options['hash'])
			;
		}

		public function __call($method, $arg)
		{
			$this->buildArg($arg['0']);
		    return $this->cpQuery($method);
		}

		private function checkSettings($options)
		{
		    if(empty($options['user']))
		      throw new \Exception('Username is not set', 2301);
		    if(empty($options['hash']))
		      throw new \Exception('Hash is not set', 2302);
		    if(empty($options['host']))
		      throw new \Exception('CPanel Host is not set', 2303);
		    return $this;
		}

		

		public function setHost($host)
		{
		    $this->host = $host;
		    return $this;
		}

		public function setAuth($user, $hash)
		{
		    $this->user = $user;
		    $this->hash = $hash;
		    return $this;
		}

		public function callHost()
		{
			return $this->host;
		}

		public function callUser()
		{
			return $this->user;
		}

		public function callHash()
		{
			return $this->hash;
		}

		public function setHeader($name, $value='')
		{
		    $this->headers[$name] = $value;
		    return $this;
		}

		public function buildArg($arg){
			$this->arg = http_build_query($arg);
			return $this;
		}

		private function makeHeader()
		{
			$headers = $this->headers;
			$user = $this->callUser();
			$hash = $this->callHash();

			return $headers['Authorization'] = 'WHM ' . $user . ':' . preg_replace("'(\r|\n)'","",$hash);
		}

		protected function cpQuery($method)
		{
			$host = $this->callHost();
			$user = $this->callUser();
			$hash = $this->callHash();
			$arg = $this->arg;
			$query = $host . ':2087/json-api/' . $method . '?api.version=1&'. $arg ;

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
			  
			$header[0] = 'Authorization: WHM ' . $user . ':' . preg_replace("'(\r|\n)'","",$hash);

			curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
			curl_setopt($curl, CURLOPT_URL, $query);
			  
			$result = curl_exec($curl);
			curl_close($curl);

		return $result;
		}
	}

 ?>
