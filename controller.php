<?php

require 'model.php';

if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['date'])) {

    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = htmlspecialchars($_POST['date']);

    if (isset($_POST['id'])) {
        $id = htmlspecialchars($_POST['id']);

        $result = update_task($id, $name, $description, $date);
        if ($result) {
            echo 'Tâche modifié';
        }
    } else {
        $result = create_task($name, $description, $date);
        if ($result) {
            echo 'Tâche ajouté';
        }
    }
}

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);

    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $result = delete_task($id);
        if ($result) {
            echo 'Tâche supprimé !';
        }
    }


    if (isset($_GET['action']) && $_GET['action'] === 'update') {
        $is_update = true;
        $task = get_task($id);
    }
}

$tasks = get_tasks();

require 'views/ID.php';
