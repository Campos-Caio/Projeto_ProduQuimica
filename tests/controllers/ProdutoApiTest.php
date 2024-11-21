<?php

use PHPUnit\Framework\TestCase;

class ProdutoApiTest extends TestCase
{
    private $baseUrl;

    protected function setUp(): void
    {
        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost/projeto-prodQuimica/src/backend/routes/router.php';
    }

    public function testListarProdutos()
    {
        $response = $this->makeRequest('GET', 'produtos');
        $this->assertEquals(200, $response['status']);
        $data = json_decode($response['body'], true);

        $this->assertNotNull($data);
        $this->assertIsArray($data, "A resposta não é um array válido de produtos.");
    }

    public function testCriarProdutoComSucesso()
    {
        $payload = [
            'nome' => 'Produto X',
            'categoria' => 'Categoria Y',
            'marca' => 'Marca Z',
            'codigo_barra' => '1234567890123',
            'preco_custo' => 10.00,
            'preco_venda' => 15.00
        ];

        $response = $this->makeRequest('POST', 'produtos', $payload);
        $this->assertEquals(201, $response['status']);

        $data = json_decode($response['body'], true);
        $this->assertEquals('Produto cadastrado com sucesso!', $data['message']);
    }

    public function testCriarProdutoComErroDeValidacao()
    {
        $payload = [
            'nome' => '', // Campo obrigatório vazio
            'preco_venda' => -1 // Valor inválido
        ];

        $response = $this->makeRequest('POST', 'produtos', $payload);
        $this->assertEquals(400, $response['status']);
        $this->assertStringContainsString('Erro de validação', $response['body']);
    }

    public function testEditarProdutoInexistente()
    {
        $payload = [
            'nome' => 'Produto X Atualizado',
            'categoria' => 'Categoria Z',
            'marca' => 'Marca W',
            'codigo_barra' => '9876543210987',
            'preco_custo' => 20.00,
            'preco_venda' => 25.00
        ];

        $response = $this->makeRequest('PUT', 'produtos/99999', $payload);
        $this->assertEquals(404, $response['status']);
        $this->assertStringContainsString('Produto não encontrado', $response['body']);
    }

    private function makeRequest($method, $endpoint, $payload = null, $headers = [])
    {
        $url = $this->baseUrl . $endpoint;
        $headers['Content-Type'] = 'application/json';
        $options = [
            'http' => [
                'method' => $method,
                'header' => $this->buildHeaders($headers),
                'content' => $payload ? json_encode($payload) : null
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

    private function buildHeaders(array $headers): string
    {
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = "$key: $value";
        }
        return implode("\r\n", $formattedHeaders);
    }
}
