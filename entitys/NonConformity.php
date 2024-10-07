<?php 

class NonConformity {
    public $orderService = "c_ordem_servico";
    public $id = "c_tag";
    public $companyId = "c_loja";
    public $inspectionDate = "dt_inspecao";
    public $performerId = "c_executante";
    public $legalRepresentantId = "c_representante_legal";
    public $revision = "c_revisao";
    public $includeDate = "dt_inclusao";
    public $includeUserId = "c_usuario_inclusao";
    public $updateDate = "dt_alteracao";
    public $updateUserId = "c_usuario_alteracao";
    public $releaseDate = "dt_liberacao";
    public $releaseUserId = "c_usuario_liberacao";

    const tableName = "t_manut_se_nao_conformidade";
}

?>