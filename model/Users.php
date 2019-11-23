<?php
	class Users extends Api
	{		
		public function CheckApiValue() {
			$db = DB::Inst();
			$id = $db->queryVal("SELECT ID FROM users WHERE ID = '".$db->esc($this->apiValue)."'");
			if(!$id) throw new Exception('Wrong user id');
		}		
		public function Delegate($modelName, $params, $urlSegments)
		{
			switch($modelName) {
				case 'Services': return $modelName::Factory($params, $urlSegments);
			}
		}
	}
?>