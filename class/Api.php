<?php
	abstract class Api extends HTTP
	{
		//protected $method = '';
		protected $params;
		protected $apiName = '';
		protected $apiValue = NULL;
		//protected $apiDelegate = NULL;
		
		static function Factory($params, $urlSegments)
		{
			$obj = new static($params);
			return $obj->Run($urlSegments);
		}
		
		public function __construct($params = NULL)
		{
			$this->params = $params !== NULL ? $params : array();
		}
		
		public function Run($urlSegments)
		{
			$this->apiName = array_shift($urlSegments);
			$this->apiValue = array_shift($urlSegments);
			if($this->apiValue !== NULL) $this->CheckApiValue();
			
			if(count($urlSegments) > 0) { 
				$pair = array($this->apiName => $this->apiValue);
				$params = array_replace($this->params, $pair);
				
				$delegate = ucfirst(current($urlSegments));
				$result = $this->Delegate($delegate, $params, $urlSegments);
				return $result !== NULL ? $result : $this->Response404();
			} else {
				$action = $this->GetAction();
				return $this->$action();
			}
		}
		protected function GetAction()
		{
			// get method
			$method = $_SERVER['REQUEST_METHOD'];
			if($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
				if($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
					$method = 'PUT';
				} else {
					throw new Exception('Method not supported');
				}
			}
			
			// get action
			$action = '';
			switch($method) {
				//case 'GET': $action = 'UpdateAction'; break;
				case 'PUT': return 'UpdateAction'; break;
				case 'GET': return $this->apiValue === NULL ? 'IndexAction' : 'ViewAction'; break;
				default: throw new Exception('Unexpected method');
			}
		}
		
		public function Delegate($modelName, $params, $urlSegments) { return $this->Response404(); }
		public function CheckApiValue() {}
		
		public function IndexAction() { return $this->ResponseError('Method Not Allowed', 405); }
		public function ViewAction() { return $this->ResponseError('Method Not Allowed', 405); }
		public function UpdateAction() { return $this->ResponseError('Method Not Allowed', 405); }
		
		
	}
?>