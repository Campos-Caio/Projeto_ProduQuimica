document.addEventListener('DOMContentLoaded', function () {
    M.AutoInit();
    loadProdutos(); 
});


async function loadProdutos() {
    try {
      const response = await fetch('http://localhost/api/clientes/listar');
      const products = await response.json();
      const productTableBody = document.getElementById('productTableBody');
      productTableBody.innerHTML = '';
  
      products.array.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${product.name}</td>
        <td>${product.categoria}</td>
        <td>${product.marca}</td>
        <td>${product.codigo_barra}</td>
        <td>${product.preco_custo}</td>
        <td>${product.preco_venda}</td>
        <td>
          <button onclick="editProduct(${product.id})" class="btn-small blue">Editar</button>
          <button onclick="deleteProduct(${product.id})" class="btn-small red">Excluir</button>
        </td>
      `;
        productTableBody.appendChild(row);
      });
    } catch (error) {
      console.error('Erro ao carregar Produtos!!');
    }
}

async function addProduct(event) {
    event.preventDefault();
  
    // Dados do formulário
    const productData = {
        nome: document.getElementById('nome').value, 
        categoria: document.getElementById('categoria').value, 
        marca: document.getElementById('marca').value, 
        codigo_barra: document.getElementById('codigo_barra').value, 
        preco_custo: document.getElementById('preco_custo').value, 
        preco_venda: document.getElementById('preco_venda').value, 
    }
  
    try{
      await fetch(`http://localhost/api/produto/criar`,{
        method : 'POST', 
        headers: {'Content-Type' : "application/json"},
        body: JSON.stringify(productData)
      });
  
      loadProdutos(); 
      document.getElementById('ProductForm').reset(); 
  
    }catch(error){
      console.error('Erro ao adicionar produto: ' ,error); 
    }
}

async function editProduct(id) {
    try{
        const response = await fetch(`http://localhost/api/produto/editar?id=${id}`); 
        const product = await response.json(); 
        
        document.getElementById('productId').value,
        document.getElementById('nome').value,
        document.getElementById('categoria').value,
        document.getElementById('marca').value,
        document.getElementById('codigo_barra').value,
        document.getElementById('preco_custo').value,
        document.getElementById('preco_venda').value,
        M.updateTextFields(); 
    }catch(error){
        console.error("Erro ao carregar produto para edicao: " ,error); 
    }
}


async function deleteProduct(id) {
    if(!confirm('Deseja realmente excluir este produto')) return; 


    try{
      const response = await fetch(`http://localhost/api/produto/excluir?id=${productId}`,{method: 'DELETE'}); 
      if(response.ok){
        loadProdutos(); 
      }else{
        console.log('Erro ao excluir cliente: ' ,response.statusText); 
      }
    }catch (error){
      console.log("Erro ao excluir cliente: " ,error); 
    }
}
