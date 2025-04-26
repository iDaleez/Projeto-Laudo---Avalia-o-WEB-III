<?php
require_once __DIR__ . '/config.php';

try {
    // Exemplo de consulta - ajuste para sua tabela real
    $stmt = $pdo->query("SELECT * FROM laudos LIMIT 10");
    $laudos = $stmt->fetchAll();
    
    // HTML básico para exibir os resultados
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Laudos Veterinários</title>
        <style>
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <h1>Laudos Veterinários</h1>
        
        <?php if (!empty($laudos)): ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($laudos[0]) as $column): ?>
                            <th><?= htmlspecialchars($column) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laudos as $laudo): ?>
                        <tr>
                            <?php foreach ($laudo as $value): ?>
                                <td><?= htmlspecialchars($value) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum laudo encontrado.</p>
        <?php endif; ?>
    </body>
    </html>
    <?php
    
} catch (PDOException $e) {
    die("Erro ao consultar o banco de dados: " . $e->getMessage());
}
?>