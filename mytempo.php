<?php
   //***********************************************************************************************************************
   // V1.0 : Script qui permet la gestion de temporisations, avec extinction automatique de lampe en option
   //*************************************** API eedomus ******************************************************************
   
   // recuperation des infos depuis la requete
  
  // n� de tempo : 1 � 20
  $tempo=getArg("tempo", $mandatory = false, $default = 1);
  // si API lampe associ�e pour extinction
  $periphId=getArg("periphId", $mandatory = false, $default = '');
  // action
  $action=getArg("action", $mandatory = false, $default = '');
  // delai
  $delai=getArg("delai", $mandatory = false, $default = 0);

   
// Lecture des tempos en variable g�n�rale de l'eedomus et d�cr�mentation
If ($action == 'xml') {
   $xml="<TEMPOS>";
   if (loadVariable('mytempo'.$tempo)) {
	 $tempoValue=loadVariable('mytempo'.$tempo);
	// D�cr�mentation automatique du d�lai
	if ($tempoValue <> 0) {
	  $tempoValue = $tempoValue - 1;
	  // Extinction de la lampe si API donn�e et tempo � 0
			if ($periphId && $periphId != 'undefined' && $tempoValue == 0) {
      			SetValue($periphId, 0);
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
   if ($action == 'init' && $delai <> 0) {
	$delai = $delai + 1;
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