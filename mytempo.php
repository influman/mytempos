<?php
   //***********************************************************************************************************************
   // V1.1 : Script qui permet la gestion de temporisations, avec extinction automatique de lampe en option
   //*************************************** API eedomus ******************************************************************
   
   // recuperation des infos depuis la requete
  
  // n� de tempo : 1 � 20
  $tempo=getArg("tempo", false, 1);
  // si API lampe associ�e pour extinction
  $periphId=getArg("periphId", false, '');
  // action
  $action=getArg("action", false, '');
  // delai
  $delai=getArg("delai", false);

   
	// Lecture des tempos en variable g�n�rale de l'eedomus et d�cr�mentation
	if ($action == 'xml') {
		$xml="<TEMPOS>";
		if (loadVariable('mytempo'.$tempo)) {
			$tempoValue=loadVariable('mytempo'.$tempo);
			// D�cr�mentation automatique du d�lai
			if ($tempoValue != 0) {
				$tempoValue = $tempoValue - 1;
				// Extinction de la lampe si API donn�e et tempo � 0
				if ($periphId && $periphId != 'undefined' && $tempoValue == 0) {
					setValue($periphId, 0);
				} 
			}
		} else {
			// mise � z�ro g�n�rale la premi�re fois
			$tempoValue = 0;
		}
		saveVariable('mytempo'.$tempo,$tempoValue);
		$xml .= "<TEMPO".$tempo.">".$tempoValue."</TEMPO".$tempo.">";
		$xml .= "</TEMPOS>";
		sdk_header('text/xml');
		echo $xml;
	}
   
	// R�initialisation demand�e du tempo d�sign�
	if ($action == 'init' && $delai != "") {
		if (is_numeric($delai)) {
			$delai = $delai + 1;
		}
		if (strpos($delai, "[") !== false) {
			$periphdelai = substr($delai, 1, -1);
			$getdelai = getValue($periphdelai);
			$delai = $getdelai['value'];
		}
		saveVariable('mytempo'.$tempo,$delai);
		die();
	}
  
	// Annulation demand�e du tempo d�sign�
	if ($action == 'stop') {
		$delai = 0;
		saveVariable('mytempo'.$tempo,$delai);
		die();
	}
?>