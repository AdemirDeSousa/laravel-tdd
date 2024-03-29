<?php


namespace App\Services\BrasilAPI\Entities;


use App\Services\BrasilAPI\Enum\CNPJSituacaoCadastral;

class CNPJ
{
    public string $cnpj;
    public string $razaoSocial;
    public string $descricaoSituacaoCadastral;

    public function __construct(array $data)
    {
        $this->cnpj = $data['cnpj'];
        $this->razaoSocial = $data['razao_social'];
        $this->descricaoSituacaoCadastral = $data['descricao_situacao_cadastral'];
    }

    public function isActive(): bool
    {
        return $this->descricaoSituacaoCadastral == CNPJSituacaoCadastral::ATIVA;
    }
}
