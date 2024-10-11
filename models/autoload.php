<?php 

spl_autoload_register(function ($className) {
    $file = str_replace('\\', '/', __dir__);

    if (!str_ends_with($className, "Builder")) {
        $file .= "/$className/$className.php" ;

        if (file_exists($file)) {
            require_once $file;
        }
        return;
    }

    $position = strpos($className, "Builder");
    $classnameWithoutBuilder = substr($className, 0, $position);

    $file .= "/$classnameWithoutBuilder/$className.php";
    if (file_exists($file)) {
        require_once $file;
    }
});

?>