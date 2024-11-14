
# Documentação da API do Sistema de Gerenciamento de Produtos Químicos

## Visão Geral
Esta documentação descreve as rotas disponíveis na API do sistema para gerenciar clientes e produtos, incluindo operações de criação, leitura, atualização e exclusão (CRUD).

## Endpoints

### 1. Clientes

#### Listar Clientes
- **Endpoint:** `GET /cliente/listar`
- **Descrição:** Retorna uma lista de todos os clientes cadastrados no sistema.
- **Resposta de Sucesso (200):** Lista de clientes em formato JSON.

#### Criar Cliente
- **Endpoint:** `POST /cliente/criar`
- **Descrição:** Adiciona um novo cliente ao sistema.
- **Dados da Requisição (JSON):**
  - `nome`: Nome completo do cliente (obrigatório)
  - `email`: Email do cliente, no formato válido (obrigatório)
  - `telefone`: Telefone do cliente, com 10 a 15 dígitos (opcional, mas recomendado)
  - `endereco`: Endereço do cliente (obrigatório)
- **Resposta de Sucesso (201):** Confirmação de que o cliente foi criado.
- **Erros Possíveis (400/500):** 
  - Campos obrigatórios faltando.
  - Erro ao salvar no banco de dados.

#### Editar Cliente
- **Endpoint:** `PUT /cliente/editar?id={id}`
- **Descrição:** Atualiza as informações de um cliente específico.
- **Parâmetro da URL:** `id` (obrigatório) - ID do cliente a ser atualizado.
- **Dados da Requisição (JSON):** Mesmo formato que a criação.
- **Resposta de Sucesso (200):** Confirmação de atualização do cliente.
- **Erros Possíveis (400/500):** 
  - ID inválido ou não encontrado.
  - Erro ao atualizar no banco de dados.

#### Excluir Cliente
- **Endpoint:** `DELETE /cliente/excluir?id={id}`
- **Descrição:** Exclui um cliente específico do sistema.
- **Parâmetro da URL:** `id` (obrigatório) - ID do cliente a ser excluído.
- **Resposta de Sucesso (200):** Confirmação de exclusão do cliente.
- **Erros Possíveis (400/500):** 
  - ID inválido ou não encontrado.
  - Erro ao excluir no banco de dados.

### 2. Produtos

#### Listar Produtos
- **Endpoint:** `GET /produto/listar`
- **Descrição:** Retorna uma lista de todos os produtos cadastrados no sistema.
- **Resposta de Sucesso (200):** Lista de produtos em formato JSON.

#### Criar Produto
- **Endpoint:** `POST /produto/criar`
- **Descrição:** Adiciona um novo produto ao sistema.
- **Dados da Requisição (JSON):**
  - `nome`: Nome do produto (obrigatório)
  - `categoria`: Categoria do produto (obrigatório)
  - `marca`: Marca do produto (obrigatório)
  - `codigo_barra`: Código de barras do produto (obrigatório)
  - `preco_custo`: Preço de custo do produto, decimal (obrigatório)
  - `preco_venda`: Preço de venda do produto, decimal (obrigatório)
- **Resposta de Sucesso (201):** Confirmação de que o produto foi criado.
- **Erros Possíveis (400/500):**
  - Campos obrigatórios faltando.
  - Erro ao salvar no banco de dados.

#### Editar Produto
- **Endpoint:** `PUT /produto/editar?id={id}`
- **Descrição:** Atualiza as informações de um produto específico.
- **Parâmetro da URL:** `id` (obrigatório) - ID do produto a ser atualizado.
- **Dados da Requisição (JSON):** Mesmo formato que a criação.
- **Resposta de Sucesso (200):** Confirmação de atualização do produto.
- **Erros Possíveis (400/500):** 
  - ID inválido ou não encontrado.
  - Erro ao atualizar no banco de dados.

#### Excluir Produto
- **Endpoint:** `DELETE /produto/excluir?id={id}`
- **Descrição:** Exclui um produto específico do sistema.
- **Parâmetro da URL:** `id` (obrigatório) - ID do produto a ser excluído.
- **Resposta de Sucesso (200):** Confirmação de exclusão do produto.
- **Erros Possíveis (400/500):** 
  - ID inválido ou não encontrado.
  - Erro ao excluir no banco de dados.

## Considerações Finais
Esta API permite a integração com o sistema para realizar o gerenciamento de clientes e produtos. Em caso de erros, a API retornará mensagens detalhadas para ajudar na identificação do problema.
