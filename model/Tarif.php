<?php
	class Tarif extends Api
	{		
		public function UpdateAction()
		{	
			// init db 
			$db = DB::Inst();
			
			// clean input
			$serviceId = $db->esc($this->params['services']);

			// get curr tarif id
			$currTarifId = $db->queryVal("SELECT tarif_id FROM services WHERE ID = '".$serviceId."'");
			
			// get group id
			$tarifGroupId = $db->queryVal("SELECT tarif_group_id FROM tarifs WHERE ID = '".$currTarifId."'");
			
			// get alowed tarifs
			$tarifs = $db->queryAll("SELECT ID, pay_period FROM tarifs WHERE tarif_group_id = '".$tarifGroupId."'");
			$tarifs = array_column($tarifs, NULL, 'ID');
						
			// check request
			$json = file_get_contents("php://input");
			if(!$json) return $this->ResponseError('Empty request', 400);
			$request = json_decode($json, true);
			if(!isset($request['tarif_id'])) return $this->ResponseError('Param `tarif_id` not set', 400);
			
			// check new tarif id
			$newTarifId = $request['tarif_id'];
			if(!is_numeric($newTarifId)) return $this->ResponseError('Param `tarif_id` should be numeric', 400);
			if(!isset($tarifs[$newTarifId])) return $this->ResponseError('Tarif not allowed for this service', 400);
			
			// calc payday		
			$payPeriod = $tarifs[$newTarifId]['pay_period'];
			$time = strtotime('midnight +'.$payPeriod.' month');
			$payday = date('Y-m-d', $time);
			
			
			$db->query("UPDATE services SET tarif_id = '".$newTarifId."', payday = '".$payday."' WHERE ID = '".$serviceId."'");
			return $this->ResponseOk();
		}
	}
?>