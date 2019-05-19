<?php
   //***********************************************************************************************************************
   // V1.1 : Script qui permet la gestion de temporisations, avec extinction automatique de lampe en option
   //*************************************** API eedomus ******************************************************************
   
   // recuperation des infos depuis la requete
  
  // n° de tempo : 1 à 20
  $tempo=getArg("tempo", false, 1);
  // si API lampe associée pour extinction
  $periphId=getArg("periphId", false, '');
  // action
  $action=getArg("action", false, '');
  // delai
  $delai=getArg("delai", false);

   
	// Lecture des tempos en variable générale de l'eedomus et décrémentation
	if ($action == 'xml') {
		$xml="<TEMPOS>";
		if (loadVariable('mytempo'.$tempo)) {
			$tempoValue=loadVariable('mytempo'.$tempo);
			// Décrémentation automatique du délai
			if ($tempoValue != 0) {
				$tempoValue = $tempoValue - 1;
				// Extinction de la lampe si API donnée et tempo à 0
				if ($periphId && $periphId != 'undefined' && $tempoValue == 0) {
					setValue($periphId, 0);
				} 
			}
		} else {
			// mise à zéro générale la première fois
			$tempoValue = 0;
		}
		saveVariable('mytempo'.$tempo,$tempoValue);
		$xml .= "<TEMPO".$tempo.">".$tempoValue."</TEMPO".$tempo.">";
		$xml .= "</TEMPOS>";
		sdk_header('text/xml');
		echo $xml;
	}
   
	// Réinitialisation demandée du tempo désigné
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
  
	// Annulation demandée du tempo désigné
	if ($action == 'stop') {
		$delai = 0;
		saveVariable('mytempo'.$tempo,$delai);
		die();
	}
?>