<?php
use MyApp\Library\View;



spl_autoload_register(function ($class) {
    
    $prefix = 'MyApp\\';

    $base_dir = __DIR__ .'/';

    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) 
    {
        return;
    }

    $relative_class = substr($class, $len);
    
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) 
    {
        require $file;
    }
});

//$classname = "MyApp\\Controller\TestDatei";
//$test = new $classname;


//index.php?controller=user&action=register

$controllerName = ucfirst(isset($_GET['controller']) ? $_GET['controller'] : "user");

$actionName = ucfirst(isset($_GET['action']) ? $_GET['action'] : "register");

$controllerClassName = "\\MyApp\\Controller\\".$controllerName."Controller";

$actionMethodName = $actionName."Action";

$controller = new $controllerClassName();

//$path, $controller, $action
$view = new MyApp\Library\View(__DIR__ .DIRECTORY_SEPARATOR."views", lcfirst($controllerName), lcfirst($actionName));

$controller->setView($view);

$controller->$actionMethodName();

$view->render();