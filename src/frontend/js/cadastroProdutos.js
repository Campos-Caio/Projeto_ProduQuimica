document.addEventListener('DOMContentLoaded', function () {
  M.AutoInit();
  loadProdutos(); 
});

async function loadProdutos() {
  try {
      const response = await fetch('http://localhost/projeto-prodQuimica/src/backend/routes/router.php?resource=produtos&action=listar');
      const products = await response.json();
      const productTableBody = document.getElementById('productTableBody');
      productTableBody.innerHTML = '';

      products.forEach(product => {
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
      console.error('Erro ao carregar Produtos!!', error);
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

  try {
      await fetch('http://localhost/projeto-prodQuimica/src/backend/routes/router.php?resource=produtos&action=cadastrar', {
          method: 'POST', 
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(productData)
      });

      loadProdutos(); 
      document.getElementById('ProductForm').reset(); 

  } catch (error) {
      console.error('Erro ao adicionar produto: ', error); 
  }
}

async function editProduct(id) {
  try {
      const response = await fetch(`http://localhost/projeto-prodQuimica/src/backend/routes/router.php?resource=produtos&action=editar&id=${id}`); 
      const product = await response.json(); 
      
      document.getElementById('productId').value = product.id;
      document.getElementById('nome').value = product.nome;
      document.getElementById('categoria').value = product.categoria;
      document.getElementById('marca').value = product.marca;
      document.getElementById('codigo_barra').value = product.codigo_barra;
      document.getElementById('preco_custo').value = product.preco_custo;
      document.getElementById('preco_venda').value = product.preco_venda;
      M.updateTextFields(); 
  } catch (error) {
      console.error("Erro ao carregar produto para edição: ", error); 
  }
}

async function deleteProduct(id) {
  if (!confirm('Deseja realmente excluir este produto?')) return; 

  try {
      const response = await fetch(`http://localhost/projeto-prodQuimica/src/backend/routes/router.php?resource=produtos&action=excluir&id=${id}`, { method: 'DELETE' }); 
      if (response.ok) {
          loadProdutos(); 
      } else {
          console.log('Erro ao excluir produto: ', response.statusText); 
      }
  } catch (error) {
      console.log("Erro ao excluir produto: ", error); 
  }
}
