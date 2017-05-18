<link rel="stylesheet" href="css/monCSS.css">
<?php
function listeHospitalisationsText(){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	$entete=array();
	$ligne=fgets($fichier);
	$entete=explode(";",$ligne);
	$etat=1;
	while(!feof($fichier)){
		if ($etat==2){
			echo "<br><b>".$entete[0]."=</b>".strtok($ligne,";");
			$taille=count($entete);
			for($i=1;$i<$taille;$i++)
				echo "<br><b>".$entete[$i]."=</b>".strtok(";");
			echo "<br>***************************************";
		}
		else{
		   $etat=2;
		}
		$ligne=fgets($fichier);
	}
	fclose($fichier);
}
function listeHospitalisationsHTML(){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	$entete=array();
	$ligne=fgets($fichier);
	$entete=explode(";",$ligne);
	echo "<table border=1>";
	echo "<caption>Liste des Hospitalisations</caption>";
	echo "<thead><tr>";
	$taille=count($entete);
	for($i=0;$i<$taille;$i++)
		echo "<th>".$entete[$i]."</th>";
	echo "</tr></thead>";
	$etat=1;
	while(!feof($fichier)){
		if ($etat==2){
		    echo "<tr>";
			$elem=strtok($ligne,";");
			while($elem!==false){
				echo "<td>".$elem."</td>";
				$elem=strtok(";");
			}
			echo "</tr>";
		}
		else{
		   $etat=2;
		}
		$ligne=fgets($fichier);
	}
	echo "</table>";
	fclose($fichier);
}
function listeHospitalisationsEtab($codeE){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	$entete=array();
	$ligne=fgets($fichier);
	$entete=explode(";",$ligne);
	echo "<table border=1>";
	echo "<caption>Liste des Hospitalisations</caption>";
	echo "<thead><tr>";
	$taille=count($entete);
	for($i=0;$i<$taille;$i++)
		echo "<th>".$entete[$i]."</th>";
	echo "</tr></thead>";
	$etat=1;
	while(!feof($fichier)){
		if ($etat==2){   
			$elem=strtok($ligne,";");
			if ($elem==$codeE){
			echo "<tr>";
			while($elem!==false){
				echo "<td>".$elem."</td>";
				$elem=strtok(";");
			}
			echo "</tr>";
		}
		}
		else{
		   $etat=2;
		}
		$ligne=fgets($fichier);
	}
	echo "</table>";
	fclose($fichier);
}

function ajouterHospit($codeEtab, $noDossPat, $dateAdmiss, $dateSort, $specialite, $typeCh){
	$fichier=fopen("hospitalisations.txt","a");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
		
	$ligne=$codeEtab.";".$noDossPat.";".$dateAdmiss.";".$dateSort.";".$specialite.";".$typeCh."\r\n";
	fwrite($fichier, $ligne);
	fclose($fichier);
}

function supprimerHosp($codeEt, $noDossP, $dateAdmi){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	$fichierCopie=fopen("copieHospitalisations.txt","w");
	$trouve = false;
	while(!feof($fichier)){
		$ligne=fgets($fichier);
		$elements=explode(";",$ligne);	
			if ($elements[0]==$codeEt && $elements[1]==$noDossP && $elements[2]==$dateAdmi){
				$trouve = true;
			}
			else{
		    fwrite($fichierCopie, $ligne);
		}
	}
	fclose($fichierCopie);
	fclose($fichier);
	unlink("hospitalisations.txt");
	rename("copieHospitalisations.txt", "hospitalisations.txt");
}

function obtenirFichePatient($codeEtb, $noDossPt, $dateAdmissn) {
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	
	$trouve = false;
	while(!feof($fichier)){
		$ligne=fgets($fichier);
		$elements=explode(";",$ligne);	
			if ($elements[0]==$codeEtb && $elements[1]==$noDossPt && $elements[2]==$dateAdmissn){
				$trouve = true;
				break;
			}			
	}	
	fclose($fichier);
	if($trouve){
		envoyerFiche($elements);
	}else{
		echo "<br>Fiche du patient introuvable";
		exit;
	}
}

function envoyerFiche($elements){
	
	echo "<div id=\"divMHosp\" name=\"divMHosp\" style=\"visibility:visible;position:absolute;top:20%;left:10%;\">\n"; 
	echo "<form name=\"formMHosp\" action=\"gestionHospitalisations.php\" method=\"post\">\n"; 
	echo "<div>Code établissement : <input type=\"text\" id=\"codeEtabl\" name=\"codeEtabl\" value=\"" . $elements[0] . "\"></div>\n"; 
	echo "<div>No dossier patient : <input type=\"text\" id=\"noDossPati\" name=\"noDossPati\" value=\"" . $elements[1] . "\"></div>\n"; 
	echo "<div>Date admission : <input type=\"text\" id=\"dateAdmissi\" name=\"dateAdmissi\" value=\"" . $elements[2] . "\"></div>\n"; 
	echo "<div>Date sortie : <input type=\"text\" id=\"dateSorti\" name=\"dateSorti\" value=\"" . $elements[3] . "\"></div>\n"; 
	echo "<div>Spécialité : <input type=\"text\" id=\"specialitee\" name=\"specialitee\" value=\"" . $elements[4] . "\"></div>\n"; 
	echo "<div>Type chambre : <input type=\"text\" id=\"typeChm\" name=\"typeChm\" value=\"" . $elements[5] . "\"></div>\n"; 
	echo "<input type=\"hidden\" name=\"monAction\" value=\"modifierDoss\">\n"; 
	echo "<input type=\"hidden\" name=\"code\" value=\"envFiche\">\n"; 
	echo "<input type=\"button\" value=\"Modifier\" onClick=\"formMHosp.submit();\">\n"; 
	echo "<img src=\"images/fermer.png\" onClick=\"document.getElementById(\"divMHosp\").style.visibility=\"hidden\";\">\n"; 
	echo "</form>\n"; 
	echo "</div><br/><br/>\n";
}

function mettreJour(){
	$codeEtabl=$_POST['codeEtabl'];
	$noDossPati=$_POST['noDossPati'];
	$dateAdmissi=$_POST['dateAdmissi'];
	$dateSorti=$_POST['dateSorti'];
	$specialitee=$_POST['specialitee'];
	$typeChm=$_POST['typeChm'];
	supprimerHosp($codeEtabl, $noDossPati, $dateAdmissi);
	ajouterHospit($codeEtabl, $noDossPati, $dateAdmissi, $dateSorti, $specialitee, $typeChm);
}

//Le controleur
$action=$_POST['monAction'];
switch($action){
	case "obtenirListe" :
		listeHospitalisationsHTML();
	break;
	case "obtenirListeEtab" :
	     $codeE=$_POST['codeE'];
		listeHospitalisationsEtab($codeE);
	break;
	case "ajouterEnregistrem" :
	     $codeEtab=$_POST['codeEtab'];
		 $noDossPat=$_POST['noDossPat'];
		 $dateAdmiss=$_POST['dateAdmiss'];
		 $dateSort=$_POST['dateSort'];
		 $specialite=$_POST['specialite'];
		 $typeCh=$_POST['typeCh'];
		 ajouterHospit($codeEtab, $noDossPat, $dateAdmiss, $dateSort, $specialite, $typeCh);
	break;
	case "supprimerDoss" :
	     $codeEt=$_POST['codeEt'];
		 $noDossP=$_POST['noDossP'];
		 $dateAdmi=$_POST['dateAdmi'];
		 supprimerHosp($codeEt, $noDossP, $dateAdmi);
	break;
	case "modifierDoss" :	    
		 $codeM = $_POST['code'];
		 if($codeM == "obtenirFiche"){
			$codeEtb=$_POST['codeEtb'];
			$noDossPt=$_POST['noDossPt'];
			$dateAdmissn=$_POST['dateAdmissn'];
			obtenirFichePatient($codeEtb, $noDossPt, $dateAdmissn);
		 }
		 else{
			 mettreJour();
		 }
		
	break;
}
echo "<br><br><a href=\"accueilHopital.html\">Retour a la page accueil</a>";
?>