<?php

// Cherche l'id
$filename = __DIR__ . "/data/todo.json";
$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';

if ($id) {
$todos = json_decode(file_get_contents($filename), true) ?? [];
if (count($todos)) {
    $todoIndex = array_search($id, array_column($todos, 'id'));
    array_splice($todos, $todoIndex, 1);
    file_put_contents($filename, json_encode($todos));
}
}

header('Location: /');