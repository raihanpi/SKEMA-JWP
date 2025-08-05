<?php

// agar sistem menyimpan ketika tambah tugas
session_start();

class tugas {
    public $id;
    public $name;
    public $completed;

    public function __construct($name) {
        $this->id = uniqid();
        $this->name = $name;
        $this->completed = false;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // tambah tugas
    if (isset($_POST['add_tugas']) && !empty($_POST['tugas_name'])) {
        $newtugas = new tugas($_POST['tugas_name']);
        $_SESSION['tugas'][] = $newtugas;
    }

    // penanda buat ceklis
    if (isset($_POST['toggle_tugas'])) {
        $tugasId = $_POST['toggle_tugas'];
        foreach ($_SESSION['tugas'] as $tugas) {
            if ($tugas->id === $tugasId) {
                $tugas->completed = !$tugas->completed;
                break;
            }
        }
    }

    // Hapus tugas
    if (isset($_POST['hapus_tugas'])) {
        $tugasId = $_POST['hapus_tugas'];
        $_SESSION['tugas'] = array_filter($_SESSION['tugas'], function($tugas) use ($tugasId) {
            return $tugas->id !== $tugasId;
        });
    }

    // biar list ga nambah pas refresh page
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">To Do List</h1>
        <div class="card">
            <div class="card-body">
                <form action="index.php" method="post" class="mb-3 d-flex">
                    <input type="text" name="tugas_name" class="form-control me-2">
                    <button type="submit" name="add_tugas" class="btn btn-primary">Tambah</button>
                </form>
                
                <ul class="list-group">
                        <?php foreach ($_SESSION['tugas'] as $tugas): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center <?= $tugas->completed ?: 'sukses' ?>">
                                <div class="form-check">
                                    <form action="index.php" method="post" class="d-inline">
                                        <input type="hidden" name="toggle_tugas" value="<?= $tugas->id ?>">
                                        <input type="checkbox" onchange="this.form.submit()" class="form-check-input" <?= $tugas->completed ? 'centang' : '' ?>>
                                        <label class="form-check-label <?= $tugas->completed ? 'text-decoration-line-through text-muted' : '' ?>">
                                            <?= htmlspecialchars($tugas->name) ?>
                                        </label>
                                    </form>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="index.php" method="post" class="d-inline">
                                        <input type="hidden" name="hapus_tugas" value="<?= $tugas->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">hapus</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                </ul>
            </div>
            
    
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>