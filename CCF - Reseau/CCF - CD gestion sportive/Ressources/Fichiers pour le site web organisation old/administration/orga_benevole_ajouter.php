<?php
//--------------------------------------------------
//  Ce script ajoute un acteur
//  page autor�f�rente prot�g�e  droits = 2
//--------------------------------------------------
require_once('../definitions.inc.php');
require_once('utile_sql.php');

include "authentification/authcheck.php" ;
if ($_SESSION['droits']<>'2') { header("Location: ../index.html");};

if( !empty($_POST['envoyer'])){

     // connexion � la base marsouin
    @mysql_connect(SERVEUR,UTILISATEUR,PASSE) or die("Connexion impossible");
    @mysql_select_db(BASE) or die("Echec de selection de la base cdt");

    if ($_POST['identite']=="") {
        echo "Vous devez indiquer un nom";
        exit;
     };
    
    if ($_POST['mode']=="insertion"){
      $insertSQL = sprintf("INSERT INTO utilisateur (login,passe,identite,email,telephone,droits,fonction,taille) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",

                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['md5'], "text"),
                       GetSQLValueString($_POST['identite'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telephone'], "text"),
                       GetSQLValueString($_POST['droits'], "int"),
                       GetSQLValueString($_POST['fonction'], "text"),
                       GetSQLValueString($_POST['taille'], "text")

	               );
      $Result1 = mysql_query($insertSQL) or die(mysql_error());
    }


    @mysql_close();

    $GoTo = "orga_benevoles.php";
    header(sprintf("Location: %s", $GoTo));
}


// d�but du fichier bandeau menu horizontal
  if (!is_readable('en_tete.html'))  die ("fichier non accessible");
  @readfile('en_tete.html') or die('Erreur fichier');

?>

<script type="text/javascript" src="jscript/cryptage_passe.js"></script>

 <style type="text/css">
    <!--
    tr {
       height: 35px;
    }
    td {
       padding: 3px;
    }
    -->
  </style>
<script language="javascript">
           // fonction pour tester la validit� de l'adresse mail
         function testMail(champ){
          if (champ.value!=""){
           mail=/^[a-zA-Z0-9]+[a-zA-Z0-9\.\-_]+@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9])+$/;
           if (!mail.test(champ.value)) {
                   alert ("L'adresse email est invalide.\nElle doit �tre de la forme xxx@xxx.xxx");
                   champ.focus();
                   return false;
           }
          }
        }

        // fonction pour interdire les caract�res num�riques
        function pasNum(e){
          if (window.event) caractere = window.event.keyCode;
               else  caractere = e.which;
                 return (caractere < 48 || caractere > 57);
          }
          
        // fonction pour autoriser uniquement les num�riques
        function pasCar(e){
          if (window.event) caractere = window.event.keyCode;
               else  caractere = e.which;
                 return (caractere == 8 || (caractere > 47 && caractere < 58));
          }

        // fonction pour mettre en majuscule
        function majuscule(champ){
        champ.value = champ.value.toUpperCase();
        }
        
        // fonction pour v�rifier la date de naissance
        function verif_nais(champ){
        if (champ.value<1930 || champ.value>2007){
            alert("Ann�e de naissance sur 4 chiffres\net comprise entre 1930 et 2007");
            champ.value = "";
            champ.focus();
          }
        }

        // fonction pour v�rifier les infos avant enregistrement
        function verif(){

         if (document.engagement.nom.value == "") {
            alert ("Nom : Le champ est obligatoire, il doit �tre renseign�");
            document.engagement.nom.focus();
            return false;
         }
         if (document.engagement.prenom.value == ""){
            alert ("Pr�nom : Le champ est obligatoire, il doit �tre renseign� ");
            document.engagement.nolicence.focus();
            return false;
         }



        }
  </script>

<div id="menu">

</div>
<div id="contenu">
     <h2>Gestion des acteurs</h2>
     <div class="item">
     <p>informations: </p>
     <form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  name="form1" onSubmit="return submit_modifpass2();">
     <input type="hidden" name="mode" value="insertion" />
     <input type='hidden' name='md5' id='md5'/>
     <table style="text-align: left; width: 600px; height: 160px;"   border="0" cellpadding="2" cellspacing="2">
     <table border="0"   style="border-collapse: collapse"  >
                      <tr>
                        <td width="30%"  style="text-align: right"><b>Nom :</b></td>
                        <td width="70%" ><input  name="identite"  style="cursor: text" size="60" value=""  onKeyPress="return pasNum(event)"/>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: right"><b>Identifiant :</b></td>
                        <td><input type="text" name="login" size="60" value=""  onKeyPress="return pasNum(event)"/></td>
                      </tr>
                      <tr>
                        <td style="text-align: right"><b>EMail :</b></td>
                        <td><input type="text" name="email" size="60" value="" onBlur="testMail(this)"></td></td>
                      </tr>
                      <tr>
                        <td style="text-align: right"><b>Fonction :</b></td>
                        <td><input type="text" name="fonction" size="60" value=""></td></td>
                      </tr>
                      <tr>
                        <td style="text-align: right"><b>Taille :</b></td>
                        <td><input  name="taille"  value="S" type="radio" />S -
                            <input  name="taille"  value="M" type="radio" checked="checked"/>M -
                            <input  name="taille"  value="L" type="radio" />L -
                            <input  name="taille"  value="XL" type="radio" />XL -
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: right"><b>T�l�phone :</b></td>
                        <td><input type="text" name="telephone" size="14" value=""></td></td>
                      </tr>
                      <tr>
                        <td style="text-align: right">
                        <b>Mot de passe  :</b></td>
                        <td><input type="password" name="passe" id="passe" value="" size="32"></td>
                      </tr>
                      <tr>
                        <td style="text-align: right">
                        <b>Confirmation Mot de passe :</b></td>
                        <td><input type="password" name="passe2" id="passe2" value="" size="32"></td>
                      </tr>
                       <tr>
                        <td style="text-align: right">
                        <b>Groupe :</b></td>
                        <td>
                        <select name="droits" id="droits">
                                <option value="0" >sans droit</option>
                                <option value="1" >Licenci�(e)</option>
                                <option value="2" selected="selected">Acteur</option>


                        </select>

                        </td>
                      </tr>

                        <td>
                        </td>
                        <td>
                        <input type="submit" value="Envoyer" name="envoyer"></td>
                      </tr>

  </table>
</form>
</div>
</div>
</div>
<div id="pied"> Site h�berg� par Endurance72 - 2, avenue
d'HAOUZA - 72000 LE MANS - T�l: 02.43.23.64.18<br />
</div>
</div>
</body></html>
