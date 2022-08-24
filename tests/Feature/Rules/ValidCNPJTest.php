<?php

namespace Tests\Feature\Rules;

use App\Rules\ValidCNPJ;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ValidCNPJTest extends TestCase
{
    /** @test */
    public function it_should_check_if_the_cnpj_is_valid_and_active()
    {
        Http::fake([
            'https://brasilapi.com.br/api/cnpj/v1/06990590000123' =>
                Http::response([
                    'cnpj' => '06.990.590/0001-23',
                    'razao_social' => 'Empresa Teste',
                    'descricao_situacao_cadastral' => 'ATIVA'
                ], 200)
        ]);

        $rule = new ValidCNPJ();

        $this->assertTrue($rule->passes('cnpj', '06990590000123'));
    }

    /** @test */
    public function return_false_if_cnpj_is_not_found_or_situation_not_actived()
    {
        Http::fake([
            'https://brasilapi.com.br/api/cnpj/v1/06990590000232' =>
                Http::response([''], 404),
            'https://brasilapi.com.br/api/cnpj/v1/0699059000025' =>
                Http::response([
                    'cnpj' => '06.990.590/0001-25',
                    'razao_social' => 'Empresa Teste',
                    'descricao_situacao_cadastral' => 'INATIVA'
                ], 200),

        ]);

        $rule = new ValidCNPJ();

        $this->assertFalse($rule->passes('cnpj', '06990590000232'));
        $this->assertFalse($rule->passes('cnpj', '06990590000235'));
    }
}
