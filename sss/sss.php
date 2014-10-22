<?php
/**
 * SUPER SIMPLE SERVER !!!
 *
 * On définie des actions, on définis des appels, et on balance le tout !!
 * 
 */
class sss{
	/**
	 * Collection des callbacks appelés
	 * @var array
	 */
	private $actions;
	/**
	 * Collection des outputs récoltés
	 * @var array
	 */
	private $out;

	/**
	 * Créée un nouvel SSS avec les $actions définies.
	 * Les fonctions seront automatiquement lancées si des appels sont définis dans $calls
	 * Le constructeur ne pouvant pas retourner les output,
	 * il suffit de procéder ainsi pour les obtenir dès l'instanciation : 
	 * $out = new sss($a,$c)->exe();
	 * @param array $actions
	 * @param array $calls
	 */
	public function __construct($actions=array(), $calls=array()){
		$this->actions=array();
		$this->out=array();
		try{
			$this->setActions($actions);
			$this->out=$this->callActions($calls);	
		}
		catch(Exception $e){
			throw new Exception("SSS/".$e->getMessage(), 1);
		}

		
	}

	/**
	 * Permet de définir une nouvelle action
	 * @param string $name
	 * @param function $callback
	 */
	public function setAction($name, $callback){
		if(isset($this->actions[$name]))
			throw new Exception("setAction($name):Name already taken", 1);
		if(!is_callable($callback, false) or is_string($callback))
			throw new Exception("setAction($name):2nd arg must be callable", 1);
		$this->actions[$name]=$callback;
		return $this;
	}

	/**
	 * Permet de définir des actions
	 * @param array $actions
	 * sous la forme [$name]=>$callback
	 * 		$name string : Nom de la function
	 * 		$callback function
	 */
	public function setActions($actions){
		try{
			foreach ($actions as $key => $value) {
				$this->setAction($key, $value);
			}	
		}
		catch(Exception $e){
			throw new Exception("setActions(".sizeof($actions).")/".$e->getMessage(), 1);
		}
		

	}

	/**
	 * Retourne l'execution de la function $name avec l'argument $args
	 * @param  string $name
	 * @param  [type] $args
	 * @return [type]
	 */
	public function callAction($name, $args){
		if(!isset($this->actions[$name]))
			throw new Exception("callAction($name):undefined action name", 1);
		$call = $this->actions[$name];
		return $call($args);
	}

	/**
	 * Retourne sous forme de tableau l'execution des fonctions
	 * Cette fonction efface les précédents retours
	 * Mais permet de retourner ceux qui sont traités lors du __construct
	 *
	 * si l'entrée "args" n'est pas définie, alors l'entrée de "calls"
	 * elle même sera utilisée comme argument.
	 * 
	 * @param  array  $calls avec pour chaques entrées
	 * 		"action" => le nom de la function a appeller
	 * 		"args" (facultatif) => l'argument à utiliser
	 * @return array qui est en fait $calls avec notement pour chaques entrées
	 * 		"action" => nom de la function qui a été appelée
	 * 		"out" => le return de la function
	 */
	public function callActions($calls=array()){
		$out = $this->out;
		foreach ($calls as $key => $value) {
			if(!isset($value["action"]))
				throw new Exception("callActions[$key]:\"action\" must be defined", 1);
			if(isset($value["args"]))
				$args=$value["args"];
			else
				$args=$value;
			$action_out=$value;
			try{
				$action_out['out']=$this->callAction($value["action"], $args);	
			}
			catch(Exception $e){
				throw new Exception("callActions(".sizeof($calls).")/".$e->getMessage(), 1);
			}
			$out[]=$action_out;
		}
		$this->out=array();
		return $out;
	}

	/**
	 * Simple alias de callActions sans arguments.
	 * Permet de retourner facilement l'out généré lors du __construct
	 * @return array qui est en fait $calls du __construct avec pour chaques entrées
	 * 		"name" => nom de la function qui a été appelée
	 * 		"args" => l'argument utilisé
	 * 		"out" => le return de la function
	 */
	public function exe(){
		try{
			return $this->callActions();
		}
		catch(Exception $e){
				throw new Exception("exe/".$e->getMessage(), 1);
		}
	}

	public function known($action){
		return isset($this->action[$action]);
	}
}
