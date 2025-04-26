<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use TCPDF as TCPDF;

if (isset($_GET['id'])) {
    try {
        // Busca o laudo no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM laudos WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $laudo = $stmt->fetch();

        if (!$laudo) {
            die("Laudo não encontrado");
        }

        // Cria novo PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configurações do documento
        $pdf->SetCreator('DiagnosysMD');
        $pdf->SetAuthor('DiagnosysMD');
        $pdf->SetTitle('Laudo Veterinário - ' . $laudo['animal_nome']);
        $pdf->SetSubject('Laudo Veterinário');
        
        // Margens
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);

        // Quebras de página automáticas
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Adiciona uma página
        $pdf->AddPage();

        // Logo
        $logo = (file_exists(__DIR__ . '/img/logo.png')) ? __DIR__ . '/img/logo.png' : null;
        if ($logo) {
            $pdf->Image($logo, 15, 10, 30, 0, 'PNG');
        }

        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'LAUDO VETERINÁRIO', 0, 1, 'C');
        $pdf->Ln(10);

        // Informações do Laudo
        $pdf->SetFont('helvetica', '', 12);

        $html = <<<EOD
        <table border="0" cellpadding="4">
            <tr>
                <td width="30%"><b>Animal:</b></td>
                <td width="70%">{$laudo['animal_nome']}</td>
            </tr>
            <tr>
                <td><b>Proprietário:</b></td>
                <td>{$laudo['proprietario']}</td>
            </tr>
            <tr>
                <td><b>Data do Exame:</b></td>
                <td>{$laudo['data_exame']}</td>
            </tr>
            <tr>
                <td><b>Veterinário Responsável:</b></td>
                <td>{$laudo['veterinario']}</td>
            </tr>
        </table>
        <br><br>
        <h4>Diagnóstico</h4>
        <p>{$laudo['diagnostico']}</p>
        <br>
        <h4>Tratamento Recomendado</h4>
        <p>{$laudo['tratamento']}</p>
        <br><br>
        <div style="text-align: right;">
            <p>_________________________________________</p>
            <p>{$laudo['veterinario']}<p>

        </div>
        EOD;

        $pdf->writeHTML($html, true, false, true, false, '');

        // Gera o PDF e envia para o navegador
        $pdf->Output('laudo_veterinario_' . $laudo['id'] . '.pdf', 'I');

    } catch (PDOException $e) {
        die("Erro ao gerar PDF: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>