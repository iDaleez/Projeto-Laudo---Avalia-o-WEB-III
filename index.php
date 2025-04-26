<?php
require_once __DIR__ . '/config.php';

// Operação de DELETE
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM laudos WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        header("Location: index.php?success=Laudo+removido+com+sucesso");
        exit();
    } catch (PDOException $e) {
        die("Erro ao excluir laudo: " . $e->getMessage());
    }
}

// Busca todos os laudos
try {
    $stmt = $pdo->query("SELECT * FROM laudos ORDER BY data_exame DESC");
    $laudos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao consultar o banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DiagnosysMD - Gestão de Laudos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .table-responsive {
      margin-top: 20px;
    }
    .table th {
      background-color: #198754;
      color: white;
    }
    .btn-action {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="logo.png" width="30" height="30" alt="">
        DiagnosysMD
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?new=1">Novo Laudo</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($_GET['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Gestão de Laudos Veterinários</h2>
      <a href="index.php?new=1" class="btn btn-success">+ Novo Laudo</a>
    </div>

    <!-- Formulário de Edição/Criação -->
    <?php if (isset($_GET['edit']) || isset($_GET['new'])): 
      $laudo = ['id' => '', 'animal_nome' => '', 'proprietario' => '', 'data_exame' => '', 
               'diagnostico' => '', 'tratamento' => '', 'veterinario' => ''];
      
      if (isset($_GET['edit'])) {
          try {
              $stmt = $pdo->prepare("SELECT * FROM laudos WHERE id = ?");
              $stmt->execute([$_GET['edit']]);
              $laudo = $stmt->fetch();
          } catch (PDOException $e) {
              die("Erro ao buscar laudo: " . $e->getMessage());
          }
      }
    ?>
      <div class="card mb-4">
        <div class="card-header bg-success text-white">
          <h5 class="card-title mb-0"><?= isset($_GET['edit']) ? 'Editar' : 'Criar' ?> Laudo</h5>
        </div>
        <div class="card-body">
          <form action="save_laudo.php" method="POST">
            <input type="hidden" name="id" value="<?= $laudo['id'] ?>">
            
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nome do Animal</label>
                <input type="text" class="form-control" name="animal_nome" value="<?= htmlspecialchars($laudo['animal_nome']) ?>" required>
              </div>
              
              <div class="col-md-6">
                <label class="form-label">Proprietário</label>
                <input type="text" class="form-control" name="proprietario" value="<?= htmlspecialchars($laudo['proprietario']) ?>" required>
              </div>
              
              <div class="col-md-4">
                <label class="form-label">Data do Exame</label>
                <input type="date" class="form-control" name="data_exame" value="<?= $laudo['data_exame'] ?>" required>
              </div>
              
              <div class="col-md-4">
                <label class="form-label">Veterinário</label>
                <input type="text" class="form-control" name="veterinario" value="<?= htmlspecialchars($laudo['veterinario']) ?>" required>
              </div>
              
              <div class="col-12">
                <label class="form-label">Diagnóstico</label>
                <textarea class="form-control" name="diagnostico" rows="3" required><?= htmlspecialchars($laudo['diagnostico']) ?></textarea>
              </div>
              
              <div class="col-12">
                <label class="form-label">Tratamento</label>
                <textarea class="form-control" name="tratamento" rows="3"><?= htmlspecialchars($laudo['tratamento']) ?></textarea>
              </div>
              
              <div class="col-12">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <!-- Tabela de Laudos -->
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Animal</th>
            <th>Proprietário</th>
            <th>Data</th>
            <th>Veterinário</th>
            <th>Ações</th>
          </tr>
        </thead>
</thead>
<tbody>
          <?php foreach ($laudos as $laudo): ?>
            <tr>
              <td><?= htmlspecialchars($laudo['animal_nome']) ?></td>
              <td><?= htmlspecialchars($laudo['proprietario']) ?></td>
              <td><?= date('d/m/Y', strtotime($laudo['data_exame'])) ?></td>
              <td><?= htmlspecialchars($laudo['veterinario']) ?></td>
              <td>
                <a href="index.php?edit=<?= $laudo['id'] ?>" class="btn btn-sm btn-primary btn-action">Editar</a>
                <a href="gerar_pdf.php?id=<?= $laudo['id'] ?>" class="btn btn-sm btn-info btn-action">PDF</a>
                <a href="index.php?delete=<?= $laudo['id'] ?>" class="btn btn-sm btn-danger btn-action" 
                   onclick="return confirm('Tem certeza que deseja excluir este laudo?')">Excluir</a>
            </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>