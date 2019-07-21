<?php

// On se facilite la vie
$ip = $_SERVER["REMOTE_ADDR"];

// Fonction qui servira à signer nos cookie pour les protéger
// Cette fonction génère 25 caractères aléatoires
function signature()
{
  $arr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $tmp = '';

  while(strlen($tmp)<25)
  {
    $tmp .= $arr[mt_rand(0,61)];
  }

  return $tmp;
}

// Ouverture de la session globale, utilisée comme espace mémoire
session_id('BS1OYs7hjlW3rRvFfJ0m');
session_start();

// 1. On vérifie si l'utilisateur aurait déjà un cookie d'administration valide
if(isset($_COOKIE['ID_ADMIN'], $_SESSION[$ip.'ID_ADMIN']) && $_COOKIE['ID_ADMIN']===$_SESSION[$ip.'ID_ADMIN'])
{
  // Si oui, on inclut la page d'administration (un document HTML par exemple)
   Header('Location: tableau.php');
  
  // On aurait pu aussi utiliser la fonction include pour simplifier le code :
  // include '/www/pages/admin/admin_interface.php';
}

// Sinon, on regarde si l'utilisateur a envoyé des données de connexion pour d'identifier
// Il est important d'appliquer la condition is_string() sur $_POST['psw'],
// parce que la fonction hash() n'admet que des chaines de caractère.

else if(isset($_POST['psw']) && is_string($_POST['psw']))
{
  // On vérifie que le mot de passe est correct avec un hash sécurisé
  // Ici, le mot de passe est : maison
  $hash = hash('sha256','#Hu5'.$_POST['psw'].'p2!B');
  if($hash=='e73c7b20a05cc0f7114e2460f1ec84061b2b333564367978e898e45eb9921346' || $hash=='ab3b27085da4504504c95546590ba79ee44e7e2e41e7cf526b8763a24e55620f')
  {
    // Si oui, on signe un cookie...
    $sign_cookie = signature();
    setcookie('ID_ADMIN', $sign_cookie);

    // ... et on le stocke dans la session globale en le protégeant
    // par l'adresse IP
    $_SESSION[$ip.'ID_ADMIN'] = $sign_cookie;

    header('Location: tableau.php'); exit;
  }

  // Redirection en cas d'erreur : mot de passe incorrect
  header('Location: index.php?error');
}
else
{
	
  ?>
  <!-- Intégrer ici une page HTML/PHP qui demande de s'authentifier avec un mot de passe -->
  <!-- Pour simplifier, je ne donne que le formulaire code brut -->
  <br><br><br><br>
  <center>
  <form action="index.php" method="post">
    <p>Mot de passe :</p>
    <input type="password" name="psw" autofocus required maxlength="100">
    <p>&nbsp;</p>
    <input type="submit" value=" Valider ">
  </form>
  <?php
  if(isset($_GET['error']))
  {
    echo 'Mot de passe incorrect';
  }
}

?></center>