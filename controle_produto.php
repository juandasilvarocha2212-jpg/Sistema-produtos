<?php
include "conexao.php";

if(isset($_POST['cadastrar'])) {
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    $valor_compra = $_POST['valor_compra'];
    $valor_venda = $_POST['valor_venda'];
    $estoque = $_POST['estoque'];

    $sql = "INSERT INTO produtos 
    (descricao, categoria, valor_compra, valor_venda, estoque)
    VALUES 
    ('$descricao', '$categoria', '$valor_compra', '$valor_venda', '$estoque')";

    $conn->query($sql);
}

if(isset($_GET['vender'])) {
    $id = $_GET['vender'];
    $conn->query("UPDATE produtos 
                  SET estoque = estoque - 1 
                  WHERE id = $id AND estoque > 0");
}

$filtro = "";
if(isset($_GET['buscar'])) {
    $filtro = $_GET['buscar'];
    $resultado = $conn->query("SELECT * FROM produtos 
                               WHERE descricao LIKE '%$filtro%' 
                               OR categoria LIKE '%$filtro%'");
} else {
    $resultado = $conn->query("SELECT * FROM produtos");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sistema Corporativo de Produtos</title>

<style>

:root {
    --bg: #f4f6f9;
    --card: #ffffff;
    --text: #1e1e1e;
    --primary: #2c3e50;
    --secondary: #34495e;
    --border: #e0e0e0;
}

.dark-mode {
    --bg: #1e1e1e;
    --card: #2a2a2a;
    --text: #f5f5f5;
    --primary: #3c3c3c;
    --secondary: #555;
    --border: #444;
}

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: var(--bg);
    color: var(--text);
    transition: 0.3s;
}

.header {
    background: var(--primary);
    color: white;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    margin: 0;
    font-size: 20px;
    letter-spacing: 1px;
}

.toggle-btn {
    background: var(--secondary);
    border: none;
    padding: 8px 14px;
    color: white;
    border-radius: 20px;
    cursor: pointer;
}

.container {
    width: 85%;
    margin: 40px auto;
}

.card {
    background: var(--card);
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    border: 1px solid var(--border);
}

.card h2 {
    margin-top: 0;
    border-bottom: 1px solid var(--border);
    padding-bottom: 10px;
}

input, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0 15px 0;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text);
    transition: 0.3s;
}

/* Corrige modo escuro no select */
select option {
    background: var(--card);
    color: var(--text);
}

button {
    background: var(--primary);
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: var(--primary);
    color: white;
    padding: 12px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid var(--border);
}

tr:hover {
    background: rgba(0,0,0,0.03);
}

.vender-btn {
    background: var(--secondary);
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
}

.vender-btn:hover {
    opacity: 0.85;
}

.buscar-area {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.buscar-area input {
    flex: 1;
}

</style>

</head>
<body>

<div class="header">
    <h1>Sistema Corporativo de Produtos</h1>
    <button class="toggle-btn" onclick="toggleMode()">🌗 Alternar Tema</button>
</div>

<div class="container">

<div class="card">
<h2>Cadastro de Produto</h2>
<form method="POST">
    <label>Descrição</label>
    <input type="text" name="descricao" required>

    <label>Categoria</label>
    <select name="categoria">
        <option>Informática</option>
        <option>Eletrônicos</option>
        <option>Outros</option>
    </select>

    <label>Valor Compra</label>
    <input type="number" step="0.01" name="valor_compra" required>

    <label>Valor Venda</label>
    <input type="number" step="0.01" name="valor_venda" required>

    <label>Estoque</label>
    <input type="number" name="estoque" required>

    <button type="submit" name="cadastrar">Cadastrar Produto</button>
</form>
</div>

<div class="card">
<h2>Inventário</h2>

<form method="GET" class="buscar-area">
    <input type="text" name="buscar" placeholder="Pesquisar...">
    <button type="submit">Buscar</button>
</form>

<table>
<tr>
    <th>Descrição</th>
    <th>Categoria</th>
    <th>Valor Venda</th>
    <th>Lucro</th>
    <th>Estoque</th>
    <th>Ação</th>
</tr>

<?php while($row = $resultado->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['descricao']; ?></td>
    <td><?php echo $row['categoria']; ?></td>
    <td>R$ <?php echo number_format($row['valor_venda'],2,',','.'); ?></td>
    <td>R$ <?php echo number_format($row['valor_venda'] - $row['valor_compra'],2,',','.'); ?></td>
    <td><?php echo $row['estoque']; ?></td>
    <td>
        <a class="vender-btn" href="?vender=<?php echo $row['id']; ?>">Vender</a>
    </td>
</tr>
<?php } ?>

</table>
</div>

</div>

<script>
function toggleMode() {
    document.body.classList.toggle("dark-mode");
    if(document.body.classList.contains("dark-mode")){
        localStorage.setItem("tema", "escuro");
    } else {
        localStorage.setItem("tema", "claro");
    }
}

window.onload = function() {
    if(localStorage.getItem("tema") === "escuro"){
        document.body.classList.add("dark-mode");
    }
}
</script>

</body>
</html>