<?php
$filename = __DIR__ . "/data/todo.json";
// Protection de l'URL
// Récupération de l'ID
// Vérifier si id existe (avec if)
$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';
if ($id) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];

    // array_search: Retourner la clé du premier élément ayant la valeur indiquée 
    // array_column: Extraire toutes les valeurs d'un tableau pour la clé indiquée
    if(count($todos)) {
        $todoIndex = array_search($id, array_column($todos, 'id'));
        $todos[$todoIndex]['done'] = !$todos[$todoIndex]['done'];
        file_put_contents($filename, json_encode($todos));
    }
}
header('Location: /');

    // header('Location: http://todoo.local/');