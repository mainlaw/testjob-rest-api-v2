<?php
	class Router extends HTTP
	{
		public function __construct()
		{
			header("Access-Control-Allow-Orgin: *");
      	header("Access-Control-Allow-Methods: *");
      	header("Content-Type: application/json");
		}
		
		private function Call($route) 
		{
			if(!$route) throw new Exception('Empty request');
			
			$basedir = 'model/';
			$urlSegments = explode('/', trim($route, '/'));
			
			if($urlSegments) {
				$modelName = ucfirst(current($urlSegments));
				if(!is_file($basedir.$modelName.'.php')) return $this->ResponseError('Model not found', 404);
				return $modelName::Factory(NULL, $urlSegments);
			} else return $this->Response404();
		}
		
		public function Run($uri = NULL)
		{
			if($uri === NULL) $uri = $_SERVER['REQUEST_URI'];
			$dir = dirname($_SERVER['SCRIPT_NAME']);
			$dir = trim($dir, '/\\');
			$dir = '/'.$dir;
			$route = preg_replace('~^'.$dir.'~', '', $uri);

			try {
				$result = self::Call($route);
			} catch(Exception $e) {
				$result = $this->ResponseError($e->getMessage(), 500);
			}
			return $result;
		}
	}
?>