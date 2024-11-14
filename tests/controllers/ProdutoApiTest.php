<?php

use PHPUnit\Framework\TestCase;

class ProdutoApiTest extends TestCase
{
    private $baseUrl = 'http://localhost:8000/api/'; 

    public function testListarProdutos()
    {
        $response = $this->makeRequest('GET', '/produtos');

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray(json_decode($response['body'], true)); 
    }

    public function testCriarProdutoComSucesso()
    {
        $payload = json_encode([
            'nome' => 'Produto X',
            'categoria' => 'Categoria Y',
            'marca' => 'Marca Z',
            'codigo_barra' => '1234567890123',
            'preco_custo' => 10.00,
            'preco_venda' => 15.00
        ]);

        $response = $this->makeRequest('POST', '/produtos', $payload);

        $this->assertEquals(201, $response['status']);
        $this->assertStringContainsString('Produto cadastrado com sucesso!', $response['body']);
    }

    public function testEditarProdutoComSucesso()
    {
        $produtoId = 1; // Defina um ID de produto válido para o teste
        $payload = json_encode([
            'nome' => 'Produto X Atualizado',
            'categoria' => 'Categoria Z',
            'marca' => 'Marca W',
            'codigo_barra' => '9876543210987',
            'preco_custo' => 20.00,
            'preco_venda' => 25.00
        ]);

        $response = $this->makeRequest('PUT', "/produtos/$produtoId", $payload);

        $this->assertEquals(200, $response['status']);
        $this->assertStringContainsString('Cadastro de produto atualizado com sucesso!', $response['body']);
    }

    public function testExcluirProdutoComSucesso()
    {
        $produtoId = 1; // Defina um ID de produto válido para o teste

        $response = $this->makeRequest('DELETE', "/produtos/$produtoId");

        $this->assertEquals(200, $response['status']);
        $this->assertStringContainsString('Cadastro de produto excluido com sucesso!', $response['body']);
    }

    private function makeRequest($method, $endpoint, $payload = null)
    {
        $url = $this->baseUrl . $endpoint;
        $options = [
            'http' => [
                'method' => $method,
                'header' => 'Content-type: application/json',
                'content' => $payload
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $status = $http_response_header[0] ?? 'HTTP/1.1 500 Internal Server Error';
        preg_match('{HTTP\/\S*\s(\d{3})}', $status, $match);

        return [
            'status' => (int) $match[1],
            'body' => $response
        ];
    }
}
