<?php

use PHPUnit\Framework\TestCase;

class ClienteApiTest extends TestCase
{
    private $baseUrl = 'http://localhost:8000/api/'; 

    public function testListarClientes()
    {
        $response = $this->makeRequest('GET', '/clientes');

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray(json_decode($response['body'], true)); 
    }

    public function testCriarClienteComSucesso()
    {
        $payload = json_encode([
            'nome' => 'Carlos Silva',
            'email' => 'carlos@example.com',
            'telefone' => '123456789',
            'endereco' => 'Rua A, 123'
        ]);

        $response = $this->makeRequest('POST', '/clientes', $payload);

        $this->assertEquals(201, $response['status']);
        $this->assertStringContainsString('Cliente cadastrado com sucesso!', $response['body']);
    }

    public function testEditarClienteComSucesso()
    {
        $clienteId = 1; // Defina um ID de cliente válido para o teste
        $payload = json_encode([
            'nome' => 'Carlos Silva Atualizado',
            'email' => 'carlos.atualizado@example.com',
            'telefone' => '987654321',
            'endereco' => 'Rua B, 456'
        ]);

        $response = $this->makeRequest('PUT', "/clientes/$clienteId", $payload);

        $this->assertEquals(200, $response['status']);
        $this->assertStringContainsString('Cadastro de cliente atualizado com sucesso!', $response['body']);
    }

    public function testExcluirClienteComSucesso()
    {
        $clienteId = 1; // Defina um ID de cliente válido para o teste

        $response = $this->makeRequest('DELETE', "/clientes/$clienteId");

        $this->assertEquals(200, $response['status']);
        $this->assertStringContainsString('Cadastro de cliente excluido com sucesso!', $response['body']);
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
