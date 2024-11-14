// Inicializar o Materialize
document.addEventListener('DOMContentLoaded', function () {
  M.FormSelect.init(document.querySelectorAll('select'));
  loadClientes(); 
});

// Alterna a exibição dos campos com base no tipo de cliente
function toggleFields() {
  const clientType = document.getElementById('clientType').value;
  document.getElementById('empresaFields').style.display = clientType === 'empresa' ? 'block' : 'none';
  document.getElementById('pessoaFisicaFields').style.display = clientType === 'pessoaFisica' ? 'block' : 'none';
}

async function loadClientes() {
  try {
    const response = await fetch('http://localhost/api/clientes/listar');
    const clientes = await response.json();
    const clientTableBody = document.getElementById('clientTableBody');
    clientTableBody.innerHTML = '';

    clientes.array.forEach(client => {
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
      </td>      <td>${client.type}</td>
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
    console.error('Erro ao carregar clientes!');
  }
}

async function saveCliente(event) {
  event.preventDefault();

  const clientType = document.getElementById('clientType').value;
  const clientData = {
    type: clientType,
    cnpj: document.getElementById('cnpj').value,
    razaoSocial: razaoSocial = document.getElementById('razaoSocial').value,
    nomeFantasia: nomeFantasia = document.getElementById('nomeFantasia').value,
    inscricaoEstadual: document.getElementById('inscricaoEstadual').value,
    nomeCompleto: document.getElementById('nomeCompleto').value,
    cpf: document.getElementById('cpf').value,
    endereco: document.getElementById('endereco').value,
    telefone: document.getElementById('telefone').value,
    email: document.getElementById('email').value
  };

  try {
    const method = clientData.id ? 'PUT' : 'POST';
    const url = clientData.id ? `http://localhost/api/clientes/${clientData.id}` : 'http://localhost/api/clientes';
    const response = await fetch(url, {
      method: method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(clienteData)
    });

    if (response.ok) {
      document.getElementById('clientForm').reset();
      toggleFields();
      loadClientes();
    } else {
      console.log('Erro ao salvar cliente:', response.statusText);
    }
  } catch (error) {
    console.log("Erro ao salvar cliente: ", error);
  }
}

async function editClient(id) {
  try{
    const response = await fetch(`http://localhost/api/clientes/editar/${id}}`); 
    const client = await response.json(); 

     clientType = document.getElementById('clientType').value = clientType;
     toggleFields(); 
     cnpj = document.getElementById('cnpj').value = client.cnpj;
     razaoSocial = document.getElementById('razaoSocial').value = razaoSocial;
     nomeFantasia = document.getElementById('nomeFantasia').value = nomeFantasia;
     inscricaoEstadual = document.getElementById('inscricaoEstadual').value = inscricaoEstadual;
     nomeCompleto = document.getElementById('nomeCompleto').value = nomeCompleto;
     cpf = document.getElementById('cpf').value = cpf;
     endereco = document.getElementById('endereco').value = endereco;
     telefone = document.getElementById('telefone').value = telefone;
     email = document.getElementById('email').value = clientEmail; 
     client.id = id; 
  }catch{
    console.error('Erro ao carregar cliente para edicao',error); 
  }
}

async function deleteClient(id) {
    try{
      const response = await fetch(`http://localhost/api/clientes/${id}`,{method: 'DELETE'}); 
      if(response.ok){
        loadClientes(); 
      }else{
        console.log('Erro ao excluir cliente: ' ,response.statusText); 
      }
    }catch (error){
      console.log("Erro ao excluir cliente: " ,error); 
    }
}

// Função para adicionar cliente à tabela
async function addClient(event) {
  event.preventDefault();

  // Dados do formulário
  const clientType = document.getElementById('clientType').value;
  const cnpj = document.getElementById('cnpj').value;
  const razaoSocial = document.getElementById('razaoSocial').value;
  const nomeFantasia = document.getElementById('nomeFantasia').value;
  const inscricaoEstadual = document.getElementById('inscricaoEstadual').value;
  const nomeCompleto = document.getElementById('nomeCompleto').value;
  const cpf = document.getElementById('cpf').value;
  const endereco = document.getElementById('endereco').value;
  const telefone = document.getElementById('telefone').value;
  const email = document.getElementById('email').value;

  const newClient = {
    type: clientType,
    nome: clientType === 'empresa' ? razaoSocial : nomeCompleto, 
    cnpj: clientType === 'empresa' ? cnpj : null, 
    cpf: clientType === 'pessoaFisica' ? cpf : null, 
    endereco: endereco, 
    telefone : telefone, 
    email : email
  };

  try{
    await fetch(`/api/clientes`,{
      method : 'POST', 
      headers: {'Content-Type' : "application/json"},
      body: JSON.stringify(newClient)
    });

    loadClientes(); 
    document.getElementById('clientForm').reset(); 
    toggleFields(); // Recolhe os campos específicos

  }catch(error){
    console.error('Erro ao adicionar cliente: ' ,error); 
  }

}
