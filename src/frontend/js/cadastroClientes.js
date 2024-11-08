// Inicializar o Materialize
document.addEventListener('DOMContentLoaded', function () {
    M.FormSelect.init(document.querySelectorAll('select'));
  });
  
  // Alterna a exibição dos campos com base no tipo de cliente
  function toggleFields() {
    const clientType = document.getElementById('clientType').value;
    document.getElementById('empresaFields').style.display = clientType === 'empresa' ? 'block' : 'none';
    document.getElementById('pessoaFisicaFields').style.display = clientType === 'pessoaFisica' ? 'block' : 'none';
  }
  
  // Função para adicionar cliente à tabela
  function addClient(event) {
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
  
    // Criação da linha para a tabela
    const clientTableBody = document.getElementById('clientTableBody');
    const newRow = document.createElement('tr');
  
    newRow.innerHTML = `
      <td>${clientType === 'empresa' ? 'Empresa' : 'Pessoa Física'}</td>
      <td>${clientType === 'empresa' ? razaoSocial : nomeCompleto}</td>
      <td>${clientType === 'empresa' ? cnpj : cpf}</td>
      <td>${endereco}</td>
      <td>${telefone}</td>
      <td>${email}</td>
    `;
  
    clientTableBody.appendChild(newRow);
  
    // Limpar formulário
    document.getElementById('clientForm').reset();
    toggleFields(); // Recolhe os campos específicos
  }
  