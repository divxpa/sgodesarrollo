<?php
/**
 *
 *
 */
class MainLayout extends TTemplateControl {

	public function onLoad($param){
		parent::onLoad($param);
		$this->ensureChildControls();

		if (!$this->Page->IsPostBack) {
			$this->Page->LoadData();
			
			if($this->Page->PagePath!="Login"){
				$this->Page->VerificarAlarmas();
			}

		}


	}

	public function btnMinimizarAlarma_OnClick($sender, $param){
		
		if(!$this->Session['ShowAlarmas']){
			$this->Session['ShowAlarmas'] = true;
			$this->btnMinimizarAlarma->CssClass = 'alarmas-min';
			$this->Page->CallbackClient->callClientFunction("ShowHide",array($this->pnlAlarmasDetalle->ClientID, true));
		}
		else{
			$this->btnMinimizarAlarma->CssClass = 'alarmas-max';
			$this->Session['ShowAlarmas'] = false;
			$this->Page->CallbackClient->callClientFunction("ShowHide",array($this->pnlAlarmasDetalle->ClientID, false));
		}

	}

}
?>