<?php

use PHPUnit\Framework\TestCase;

class ClienteApiTest extends TestCase
{
    private $baseUrl;

    protected function setUp(): void
    {
        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost/projeto-prodQuimica/src/backend/routes/router.php';
    }

    public function testListarClientes()
    {
        $response = $this->makeRequest('GET', 'clientes');
        $this->assertEquals(200, $response['status']);
        $data = json_decode($response['body'], true);

        $this->assertNotNull($data);
        $this->assertIsArray($data, "A resposta não é um array válido de clientes.");
    }

    public function testCriarClienteComSucesso()
    {
        $payload = [
            'nome' => 'Carlos Silva',
            'email' => 'carlos@example.com',
            'telefone' => '123456789',
            'endereco' => 'Rua A, 123'
        ];

        $response = $this->makeRequest('POST', 'clientes', $payload);
        $this->assertEquals(201, $response['status']);

        $data = json_decode($response['body'], true);
        $this->assertEquals('Cliente cadastrado com sucesso!', $data['message']);
    }

    public function testCriarClienteComErroDeValidacao()
    {
        $payload = [
            'nome' => '', // Campo obrigatório vazio
            'email' => 'email_invalido',
        ];

        $response = $this->makeRequest('POST', 'clientes', $payload);
        $this->assertEquals(400, $response['status']);
        $this->assertStringContainsString('Erro de validação', $response['body']);
    }

    public function testEditarClienteInexistente()
    {
        $payload = [
            'nome' => 'Carlos Silva Atualizado',
            'email' => 'carlos.atualizado@example.com',
            'telefone' => '987654321',
            'endereco' => 'Rua B, 456'
        ];

        $response = $this->makeRequest('PUT', 'clientes/99999', $payload);
        $this->assertEquals(404, $response['status']);
        $this->assertStringContainsString('Cliente não encontrado', $response['body']);
    }

    public function testExcluirClienteSemAutenticacao()
    {
        $response = $this->makeRequest('DELETE', 'clientes/1', null, ['Authorization' => '']); // Sem token
        $this->assertEquals(401, $response['status']);
        $this->assertStringContainsString('Não autorizado', $response['body']);
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
