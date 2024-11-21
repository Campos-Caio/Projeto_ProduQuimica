// Inicializar o Materialize
document.addEventListener('DOMContentLoaded', function () {
  M.FormSelect.init(document.querySelectorAll('select'));
  loadClientes();
  toggleFields();
});

// URL base para requisições à API
const BASE_URL = 'http://localhost/projeto-prodQuimica/src/backend/routes/router.php?resource=clientes';

// Alterna a exibição dos campos com base no tipo de cliente
function toggleFields() {
  const clientType = document.getElementById('clientType').value;
  document.getElementById('empresaFields').style.display = clientType === 'empresa' ? 'block' : 'none';
  document.getElementById('pessoaFisicaFields').style.display = clientType === 'pessoaFisica' ? 'block' : 'none';
}

// Carrega clientes e popula a tabela
async function loadClientes() {
  try {
    const response = await fetch(`${BASE_URL}&action=listar`);
    if (!response.ok) throw new Error(`Erro ao listar clientes: ${response.statusText}`);
    
    const clientes = await response.json();
    const clientTableBody = document.getElementById('clientTableBody');
    clientTableBody.innerHTML = '';

    clientes.forEach(client => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${client.type}</td>
        <td>${client.name || client.businessName}</td>
        <td>${client.cnpj || client.cpf}</td>
        <td>${client.address}</td>
        <td>${client.phone}</td>
        <td>${client.email}</td>
        <td>
          <button onclick="editClient(${client.id})" class="btn-small blue">Editar</button>
          <button onclick="deleteClient(${client.id})" class="btn-small red">Excluir</button>
        </td>
      `;
      clientTableBody.appendChild(row);
    });
  } catch (error) {
    console.error('Erro ao carregar clientes:', error);
  }
}

// Salva ou atualiza cliente (POST ou PUT)
async function saveCliente(event) {
  event.preventDefault();

  const clientType = document.getElementById('clientType').value;
  const clientData = {
    type: clientType,
    nome: clientType === 'empresa' ? document.getElementById('razaoSocial').value : document.getElementById('nomeCompleto').value,
    cnpj: clientType === 'empresa' ? document.getElementById('cnpj').value : null,
    cpf: clientType === 'pessoaFisica' ? document.getElementById('cpf').value : null,
    endereco: document.getElementById('endereco').value,
    telefone: document.getElementById('telefone').value,
    email: document.getElementById('email').value,
  };

  const method = clientData.id ? 'PUT' : 'POST';
  const action = clientData.id ? `editar&id=${clientData.id}` : 'cadastrar';

  try {
    const response = await fetch(`${BASE_URL}&action=${action}`, {
      method: method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(clientData),
    });

    if (response.ok) {
      document.getElementById('clientForm').reset();
      toggleFields();
      loadClientes();
    } else {
      throw new Error(`Erro ao salvar cliente: ${response.statusText}`);
    }
  } catch (error) {
    console.error('Erro ao salvar cliente:', error);
  }
}

// Edita cliente, preenchendo o formulário com os dados do cliente existente
async function editClient(id) {
  try {
    const response = await fetch(`${BASE_URL}&action=buscar&id=${id}`);
    if (!response.ok) throw new Error(`Erro ao buscar cliente: ${response.statusText}`);
    
    const client = await response.json();

    // Preenche os campos do formulário
    document.getElementById('clientType').value = client.type;
    toggleFields();
    document.getElementById('cnpj').value = client.cnpj || '';
    document.getElementById('razaoSocial').value = client.razaoSocial || '';
    document.getElementById('nomeCompleto').value = client.nomeCompleto || '';
    document.getElementById('cpf').value = client.cpf || '';
    document.getElementById('endereco').value = client.endereco || '';
    document.getElementById('telefone').value = client.telefone || '';
    document.getElementById('email').value = client.email || '';
    clientData.id = client.id; // Armazena o ID para a edição
  } catch (error) {
    console.error('Erro ao carregar cliente para edição:', error);
  }
}

// Exclui cliente
async function deleteClient(id) {
  try {
    const response = await fetch(`${BASE_URL}&action=excluir&id=${id}`, { method: 'DELETE' });
    if (response.ok) {
      loadClientes();
    } else {
      throw new Error(`Erro ao excluir cliente: ${response.statusText}`);
    }
  } catch (error) {
    console.error('Erro ao excluir cliente:', error);
  }
}
