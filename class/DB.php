<?php
	class DB extends mysqli
	{
		private static $inst;
		
		public static function Inst() {
			if(static::$inst === NULL) static::$inst = new static(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			return static::$inst;
		}	
		
		public function query($q, $resultmode = NULL)
		{
			$result = parent::query($q, $resultmode);
			if(!$result) {
				error_log("Mysql error: ".$this->error);
				throw new Exception('Database error');
			}
			return $result;
		}
		public function queryAll($q, $resultmode = NULL) 
		{
			$result = $this->query($q, $resultmode);
			$all = $result->fetch_all(MYSQLI_ASSOC);
			$all = TextLib::ToUTF8($all);
			return $all;
		}
		public function queryOne($q, $resultmode = NULL) 
		{
			$result = $this->query($q, $resultmode);
			$one = $result->fetch_assoc();
			$one = TextLib::ToUTF8($one);
			return $one;
		}
		public function queryVal($q, $resultmode = NULL) 
		{
			$row = $this->queryOne($q, $resultmode);
			if(!$row) return NULL;
			return TextLib::ToUTF8(current($row));
		}
		public function esc($str) {
			return $this->escape_string($str);
		}
	}
?>