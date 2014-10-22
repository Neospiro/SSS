<form action="" method="post">
	<label>string:<input type="text" placeholder="Ecrivez n'importe quoi" name='string'></label><br/>
	<label>action:valid<input hidden name="action" value='valid'></label><br/>
	<input type="submit">
</form>
<form action="" method="post">
	<label>action:unknown_func<input hidden name="action" value='unknown_func'></label><br/>
	<input type="submit">
</form>
<?php
//On inclue le SUPER SIMPLE SERVER
include "sss/sss.php";
function json_dump($var){
	echo "<pre>".json_encode($var,JSON_PRETTY_PRINT )."</pre>";
}

//On set les deux tableaux
//Un pour les fonctions et un pour les appels de fonction
$actions=array();
$calls=array();

//On définie une function "valid"
$actions["valid"]=function($args){
	echo "L'utilisateur a ecrit ".$args["string"];
	if($args["string"]		=="n'importe quoi"){
		return "c'est le bordel";
	}
	else if($args["string"]	==""){
		return "c'est pas du tout bon";
	}
	else return 'tout est ok';
};

//Si l'utilisateur DEMANDE une action, on peut alors l'utiliser
if(isset($_POST["action"]))
	$calls[]=$_POST;

//On termine en lançant le serveur avec tous les bons paramêtre, sans même avoir à le stocker.
//On l'utilise alors comme une sorte de grosse fonction
try{
	json_dump(
		(new sss($actions, $calls))->exe()
	);
}
catch (Exception $e)
{
	echo 'Erreur : '.$e->getMessage();
}
