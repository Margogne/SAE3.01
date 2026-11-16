<?php

function get_bdd()
{
    $serveur = "localhost";
    $base = "TD5";
    $user = "root";
    $password = "";

    $dsn = "mysql:host=$serveur;dbname=$base;charset=utf8mb4";
    $pdo =  new PDO($dsn, $user, $password);

    return $pdo;
}

function get_tasks()
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM tasks');
    $tasks = $query->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}

function get_task($id)
{
    $bdd = get_bdd();
    $query = $bdd->query('SELECT * FROM tasks WHERE id = ' . $id);
    $task = $query->fetch(PDO::FETCH_ASSOC);
    return $task;
}



function create_task($name, $description, $echeance)
{
    $bdd = get_bdd();
    $query = $bdd->prepare('INSERT INTO `tasks` (`id`, `name`, `description`, `date`) VALUES (NULL, ?, ?, ?);');
    $result = $query->execute(array($name, $description, $echeance));
    return $result;
}

function delete_task($id)
{
    $bdd = get_bdd();
    $query = $bdd->prepare('DELETE FROM tasks WHERE `tasks`.`id` = ?');
    $result = $query->execute(array($id));
    return $result;
}

function update_task($id, $name, $description, $echeance)
{
    $bdd = get_bdd();
    $query = $bdd->prepare('UPDATE `tasks` SET `name` = ?, `description` = ?, `date` = ? WHERE `tasks`.`id` = ?;');
    $result = $query->execute(array($name, $description, $echeance, $id));
    return $result;
}
