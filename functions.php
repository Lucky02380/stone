<?php

function createUser($username, $password) {
    global $db;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $db->query("INSERT INTO users VALUES('$username','$hashedPassword',0)");
}

function getUser($username) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateScore($username, $score) {
    global $db;
    $stmt = $db->prepare("UPDATE users SET score = ? WHERE username = ?");
    $stmt->execute([$score, $username]);
}

function getTopUsers($limit = 3) {
    global $db;
    $stmt = $db->prepare("SELECT username, score FROM users ORDER BY score DESC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
