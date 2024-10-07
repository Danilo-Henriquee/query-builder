<?php require_once("./entitys/NonConformity.php") ?>
<?php require_once("./QueryBuilder.php") ?>

<?php 

$query = new QueryFactory($db, $usuarioLogado, $lojaLogado);
$query->initialize(NonConformity::class)->newSelectQuery()
    ->select(["c_loja"])
    ->from()
    ->leftJoin(
        "t_comercial_ordem_servico",
        ["=", "c_ordem_servico"],
        [["d_ordem_servico", "realOrderService"]]
    )
    ->leftJoin(
        "t_comercial_ordem_servicooplaaa",
        ["=", "c_ordem_servico"],
    )
    ->where([
        ["c_tag", "=", "ola"]
    ])
    ->execute();
?>