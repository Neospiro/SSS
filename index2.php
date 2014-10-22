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
define("BR", "<br/>");

//on instancie le serveur
$server = new sss();

//On définie une function "valid"
$server->setAction("valid",function($args){
	echo "L'utilisateur a ecrit ".$args["string"];
	if($args["string"]		=="n'importe quoi"){
		return "c'est le bordel";
	}
	else if($args["string"]	==""){
		return "c'est pas du tout bon";
	}
	else return 'tout est ok';
});

echo "Test declarer 2 fois la meme action : ".BR;
try{
	$server->setAction("valid", function(){});
} catch(Exception $e){
	echo $e->getMessage().BR.BR;
}


//Si l'utilisateur DEMANDE une action, on peut alors l'utiliser
if(isset($_POST["action"]))
	try{
		json_dump(
			$server->callAction($_POST["action"], $_POST)
		);
	}
	catch (Exception $e)
	{
		echo 'Erreur : '.$e->getMessage();
	}
