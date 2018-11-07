<?php
/*
In essance, this is a controller, of an MVC system.
*/
class API {
	/**************
	PRIVATE
	**************/
	/**************
	PUBLIC
	**************/
	public function exec() {
		$data = array();
		if (!isset($_REQUEST["c"])){
			$data["errors"]["controller"] = "No Controller Specified.";
		} else {
			$controller = $_REQUEST["c"];
			$controllerFile = "../protected/controllers/".$controller.".php";
			$className = $controller."Controller";
			if (file_exists($controllerFile)){
				require_once($controllerFile);
				if (class_exists($className)){
					$controller = new $className();
					if (method_exists($controller,"execute")){
						$data = $controller->execute($data);
					} else {
						$data["errors"]["controller"] = "Missing execute method.";
					}
				} else {
					$data["errors"]["controller"] = "Class does not exist.";
				}
			} else {
				$data["errors"]["controller"] = "File does not exist.";
			}
		}
		return $data;
	}
}