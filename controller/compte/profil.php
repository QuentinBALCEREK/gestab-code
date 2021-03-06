<?php

include('model/adresse.php');
include('model/entite.php');
include('model/internaute.php');

if (isset($_SESSION['user']))
{
  if (isset($_POST['modifier']))
  {
      if(!empty($_POST['modifier']))
       {
          $idInternaute = $_SESSION['user']['inter_id'];
          $idAdresse    = $_SESSION['user']['adresse']['adr_id'];
          $no           = $_POST['nom'];
          $pre          = $_POST['prenom'];
          $mail         = $_POST['email'];
          $tel          = $_POST['telephone'];
          $numru        = $_POST['numrue'];
          $ru           = $_POST['rue'];
          $vil          = $_POST['ville'];
          $cp           = $_POST['codepostal'];
          $mdp          = sha1($_POST['mdp']);
          
          //Les modifications dans la bdd
          updateInternautemdp($bdd, $idInternaute, $no, $pre, $mail, $tel, $mdp);
          updateAdresse($bdd, $idAdresse, $numru, $ru, $vil, $cp);
        }
        else
        {
          $idInternaute = $_SESSION['user']['inter_id'];
          $idAdresse    = $_SESSION['user']['adresse']['adr_id'];
          $no           = $_POST['nom'];
          $pre          = $_POST['prenom'];
          $mail         = $_POST['email'];
          $tel          = $_POST['telephone'];
          $numru        = $_POST['numrue'];
          $ru           = $_POST['rue'];
          $vil          = $_POST['ville'];
          $cp           = $_POST['codepostal'];
          
          //Les modifications dans la bdd
          updateInternaute($bdd, $idInternaute, $no, $pre, $mail, $tel);
          updateAdresse($bdd, $idAdresse, $numru, $ru, $vil, $cp);
        }
      if ($_SESSION['user']['entite'] != false)
      {
        $identite          = $_SESSION['user']['entite']['entite_id'];
        $entite            = $_SESSION['user']['entite']['entite_nom'];
        $societeadrid      = $_SESSION['user']['entite']['adresse']['adr_id'];
        $societeRue        = $_SESSION['user']['entite']['adresse']['adr_rue'];
        $societeVille      = $_SESSION['user']['entite']['adresse']['adr_ville'];
        $societeCodepostal = $_SESSION['user']['entite']['adresse']['adr_code_postal'];

        if (isset($_POST['entite']))
        {
          $enom = $_POST['entite'];
          $evil = $_POST['entiteVille'];
          $eru  = $_POST['entiteRue'];
          $ecp  = $_POST['entiteCp'];

          updateEntite($bdd, $identite, $enom);
          updateAdresse($bdd, $societeadrid, $numru, $eru, $evil, $ecp);
        }
      }

      //Insertion des la session
      $internaute            = getInternaute($bdd, $idInternaute);
      $internaute['adresse'] = getAdresse($bdd, $internaute['inter_adr_id']);
      $internaute['entite']  = getEntite($bdd, $internaute['inter_entite_id']);
      if ($internaute['entite'] != false)
        $internaute['entite']['adresse'] = getAdresse($bdd, $internaute['entite']['entite_id']);
      unset($internaute['adresse_adr_id'], $internaute['inter_entite_id']);
      $_SESSION['user'] = $internaute;

      success("<strong>Félicitation!</strong> le compte a bien été modifié les changements seront pris en compte lors de la prochaine connexion.");
  }

  if (isset($_POST['supprimer']))
  {
    $req = $bdd->prepare('UPDATE internaute SET inter_datsup = NOW() WHERE inter_id = :interid');
    $req->execute(array(
      'interid' => $interid,
      ));
    success("<strong>Félicitation!</strong> le compte a bien été supprimé.");
  }

  $nom         = $_SESSION['user']['inter_nom'];
  $interid     = $_SESSION['user']['inter_id'];
  $email       = $_SESSION['user']['inter_mail'];
  $prenom      = $_SESSION['user']['inter_prenom'];
  $telephone   = $_SESSION['user']['inter_telephone'];
  $numrue      = $_SESSION['user']['adresse']['adr_num_rue'];
  $rue         = $_SESSION['user']['adresse']['adr_rue'];
  $ville       = $_SESSION['user']['adresse']['adr_ville'];
  $codepostal  = $_SESSION['user']['adresse']['adr_code_postal'];
  if ($_SESSION['user']['entite'] != false)
  {
    $entite      = $_SESSION['user']['entite']['entite_nom'];
    $entiteRue   = $_SESSION['user']['entite']['adresse']['adr_rue'];
    $entiteVille = $_SESSION['user']['entite']['adresse']['adr_ville'];
    $entiteCp    = $_SESSION['user']['entite']['adresse']['adr_code_postal'];
  }

  $lead        = $_SESSION['user']['inter_nom'];
  $tagline     = "Bienvenue sur votre espace perso";
  $breadcrumbs = array("Profil");
  $pageInclude = "compte/profil.php";
}
else
{
  redirection('index.php');
}
?>