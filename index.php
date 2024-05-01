<?php
const ERROR_REQUIRED = 'Veuillez renseigner une todo';
const ERROR_TOO_SHORT = 'Veuillez entrer au moins 5 caractères';
$error = '';
// Ajout de la logique de sauvegarde des todos
$todo = '';

// Permet d'obtenir le chemin absolu vers le fichier todo.json
$filename = __DIR__ . "/data/todo.json";
$todos = [];

// Récupération des todos existantes (= si fichier json existe alors au moins 1 todo de sauvegardée dans ce fichier)
// Récupération du contenu avec file_get_contents
// Décoder le fichier car format json + true en 2nd argument pour obtenir tableau associatif lors de la conversion
if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}

// Vérification, validation et nettoyage des données via la requête POST
// Définition des variables d'erreurs correspondantes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, [
        "todo" => [
            "filter" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            "flags" => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK
        ]
        ]);
        $todo = $_POST['todo'] ?? '';


    if (!$todo) {
        $error = ERROR_REQUIRED;
    } else if (mb_strlen($todo) < 5) {
        $error = ERROR_TOO_SHORT;
    }

// Effectue la sauvegarde que si il y a une erreur
// ...$todos récupère les élements du tableau associatif $todos et les mettre au début du nouveau tableau
// Permet ajouter une nouvelle todo à la suite
// Encodage du nouveau tableau asso en json qui écrse le contenu précèdent
if (!$error) {
    $todos = [...$todos, [
        'name' => $todo,
        'done' => false,
        'id' => time()
    ]];
    file_put_contents($filename, json_encode($todos));
    $todo = '';
    header('Location: /');
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
<?php require_once __DIR__ . '/includes/head.php' ?>
<title>Todoo</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<div class="container">

<?php require_once __DIR__ . '/includes/header.php' ?>

<div class="content">
    <div class="todo-container">
        <h1>Ma Todoo</h1>
        <form action="/" method="POST" class="todo-form">
            <input value="<?= $todo ?>" type="text" name="todo">
            <button class="btn btn-primary  custom-button"><i class="bi bi-plus-circle-fill"></i></button>
        </form>
        <!-- Message d'erreur qui s'affiche que en cas d'erreur -->
        <?php if ($error) : ?>
            <p class="text-danger"><?= $error ?></p>
        <?php endif; ?>
    <!-- Parcourir le tableau  -->
        <ul class="todo-list">
            <?php foreach($todos as $t): ?>
                <li class="todo-item <?= $t['done'] ? 'low-opacity' : '' ?>">
                    <span class="todo-name"><?= $t['name'] ?></span>
                    <a href ="/edit-todo.php?id=<?= $t['id'] ?>">
                        <button class="btn btn-valid"><?= $t['done'] ? '<i class="bi bi-x-circle"></i>' : '<i class="bi bi-check2"></i>'?></button>
                    </a>
                    <a href="/remove-todo.php?id=<?= $t['id'] ?>">
                    <button class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


<?php require_once __DIR__ . '/includes/footer.php' ?>

</div>
</body>
</html>

