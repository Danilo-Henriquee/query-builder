<?php
    $router->get("/nonConformity")->handler(function ($queryFactory, mixed &...$params) {
        $items = $queryFactory->initialize(NonConformity::class)->newSelectQuery()
            ->select(["c_loja"])
            ->from()
            ->where([["c_ordem_servico", "=", 6357]])
            ->execute();

        exit(json_encode($items));
    });
?>