<?php
	class Services extends Api
	{		
		public function CheckApiValue() {
			$db = DB::Inst();
			$id = $db->queryVal("SELECT ID FROM services WHERE user_id = '".$db->esc($this->params['users'])."' AND ID = '".$db->esc($this->apiValue)."'");
			if(!$id) throw new Exception('Wrong user service id');
		}
				
		public function Delegate($modelName, $params, $urlSegments)
		{
			switch($modelName) {
				case 'Tarifs': 
				case 'Tarif': return $modelName::Factory($params, $urlSegments);
			}
		}
	}
?>