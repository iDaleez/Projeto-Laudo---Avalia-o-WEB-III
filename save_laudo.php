<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            // Atualizar laudo existente
            $stmt = $pdo->prepare("UPDATE laudos SET 
                animal_nome = ?, 
                proprietario = ?, 
                data_exame = ?, 
                diagnostico = ?, 
                tratamento = ?, 
                veterinario = ? 
                WHERE id = ?");
                
            $stmt->execute([
                $_POST['animal_nome'],
                $_POST['proprietario'],
                $_POST['data_exame'],
                $_POST['diagnostico'],
                $_POST['tratamento'],
                $_POST['veterinario'],
                $_POST['id']
            ]);
            
            $message = "Laudo atualizado com sucesso";
        } else {
            // Criar novo laudo
            $stmt = $pdo->prepare("INSERT INTO laudos 
                (animal_nome, proprietario, data_exame, diagnostico, tratamento, veterinario) 
                VALUES (?, ?, ?, ?, ?, ?)");
                
            $stmt->execute([
                $_POST['animal_nome'],
                $_POST['proprietario'],
                $_POST['data_exame'],
                $_POST['diagnostico'],
                $_POST['tratamento'],
                $_POST['veterinario']
            ]);
            
            $message = "Laudo criado com sucesso";
        }
        
        header("Location: index.php?success=" . urlencode($message));
        exit();
    } catch (PDOException $e) {
        die("Erro ao salvar laudo: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>