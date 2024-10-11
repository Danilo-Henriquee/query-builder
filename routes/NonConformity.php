<?php
    $router->get("/nonConformity/")->handler(function ($queryFactory, $params) {
        $factory = $queryFactory->initialize(NonConformity::class);
        
        $factory->appendExpectedWhereParams(
            Params::builder()
            ->queryStringUrlName("orderService")
            ->dbColumnName("c_ordem_servico")->operator("=")->attach()
            ->queryStringUrlName("startDate")
            ->dbColumnName("dt_inspecao")->operator(">=")->attach()
            ->queryStringUrlName("endDate")
            ->dbColumnName("dt_inspecao")->operator("<=")->attach()
            ->build()
        );
        
        $conditions = $factory->bindQueryStringsToWhereConditions($params);

        $items = $factory->newSelectQuery()
            ->select(onlyColumns: 
                Columns::builder()
                ->column("c_tag")->as("nonConformityId")
                ->column("c_ordem_servico")->as("realOrderService")
                ->column("dt_inspecao")->as("inspectionDate")
                ->build()
            )
            ->from()
            ->where([...$conditions])
            ->leftJoin(
                LeftJoin::builder()
                ->tableName("t_comercial_ordem_servico")
                ->columnName("c_ordem_servico")
                ->comparisonOperator("=")
                ->columnsToJoin(
                    Columns::builder()
                    ->column("d_ordem_servico")->as("orderService")
                    ->build()
                )
                ->build()
            )
            ->leftJoin(
                LeftJoin::builder()
                ->tableName("t_pessoa")
                ->columnName("c_pessoa")
                ->overrideTopTableAndCompareTo("t_comercial_ordem_servico")->usingColumn("c_cliente")
                ->comparisonOperator("=")
                ->columnsToJoin(
                    Columns::builder()
                    ->column("d_nome_completo")->as("companyName")
                    ->build()
                )
                ->build()
            )
            ->execute();

        exit(json_encode($items));
    });
?>