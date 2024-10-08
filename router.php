<?php header("Content-Type: text/html; charset=utf-8",true); ?>
<?php header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');?>
<?php header('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, authorization, X-Requested-With');?>

<?php require_once("./router/Router.php") ?>
<?php require_once("./QueryFactory.php") ?>
<?php require_once("./entitys/NonConformity.php") ?>

<?php 
$router = new Router();
$queryFactory = new QueryFactory($db, $usuarioLogado, $lojaLogado);

$router->setQueryFactory($queryFactory);

require("./routes/NonConformity.php");

$router->router(
    $_SERVER["REQUEST_METHOD"],
    $_SERVER["PATH_INFO"],
    $_GET
);
?>