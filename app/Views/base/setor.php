<!DOCTYPE html>
<html>
<head>
    <title>Setores</title>
    <!-- Adicione os links para o Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Tabela de Setores</h1>
        <form method="GET" action="<?= base_url('central/setores/' . $pagination['currentPage']) ?>">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Pesquisar em todos os campos" name="search" value="<?= $search ?>">
        <button class="btn btn-primary" type="submit">Pesquisar</button>
    </div>
</form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <?php foreach ($setores as $setor): ?>
                        <?php foreach ($setor as $column => $value): ?>
                            <th><?= ucfirst($column) ?></th>
                        <?php endforeach; ?>
                        <?php break; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($setores as $setor): ?>
                    <tr>
                        <?php foreach ($setor as $value): ?>
                            <td><?= $value ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Pagination">
    <ul class="pagination">
        <?php
        $totalPages = $pagination['totalPages'];
        $currentPage = $pagination['currentPage'];

        $startPage = max($currentPage - 4, 1);
        $endPage = min($currentPage + 5, $totalPages);

        if ($currentPage > 1) {
            echo '<li class="page-item">
                    <a class="page-link" href="' . base_url('central/setores/' . ($currentPage - 1)) . '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">
                    <a class="page-link" href="' . base_url('central/setores/' . $i) . '">' . $i . '</a>
                </li>';
        }

        if ($currentPage < $totalPages) {
            echo '<li class="page-item">
                    <a class="page-link" href="' . base_url('central/setores/' . ($currentPage + 1)) . '" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>';
        }
        ?>
    </ul>
</nav>


    <!-- Adicione os links para o Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
