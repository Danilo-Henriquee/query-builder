<?php
    $router->get("/nonConformity")->handler(function ($queryFactory, $params) {
        $factory = $queryFactory->initialize(NonConformity::class);

        $factory->appendExpectedParam("orderService", "c_ordem_servico", "=");
        $factory->appendExpectedParam("startDate", "dt_inspecao", ">=");
        $factory->appendExpectedParam("endDate", "dt_inspecao", "<=");

        $conditions = $factory->bindWhereConditions($params);

        $items = $factory->newSelectQuery()
            ->select(["c_loja"])
            ->from()
            ->where([...$conditions])
            ->execute();

        exit(json_encode($items));
    });
?>