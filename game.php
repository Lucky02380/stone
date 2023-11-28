<?php
session_start();
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// global $username;
$username = $_SESSION['username'];
$user = getUser($username);
$score = $user['score'];
$status = "";
// echo $username;
// die;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST["play"])){
        //game logic here and update the user's score
        $choices = ['rock', 'paper', 'scissors'];
        $userChoice = isset($_POST['user_choice']) ? $_POST['user_choice'] : '';
        $computerChoice = '';

        // echo "trace1";
        // die;
        // Function to get computer's choice
        function getComputerChoice(){
            global $choices;
            return $choices[array_rand($choices)];
        }

        // Function to determine the winner
        function determineWinner($userChoice, $computerChoice){
            global $username, $score, $status;
            if($userChoice == $computerChoice){
                $status = "Tie";
            }
            else if (($userChoice == 'rock' && $computerChoice == 'scissors') || ($userChoice == 'paper' && $computerChoice == 'rock') || ($userChoice == 'scissors' && $computerChoice == 'paper')) {
                // echo $user['score'];
                // die;
                $score++; //ERROR
                updateScore($username, $score);
                $status = "Win";
                return 1;
            } else {
                $status = "Lose";
                return 0;
            }
        }
        
        // Process user's choice
        if ($userChoice) {
            $computerChoice = getComputerChoice();
            $result = determineWinner($userChoice, $computerChoice);

            if($result){
                $user['score']++;
            }
        }

        if($status == "Lose"){
            echo "<h2 style='color: red'>You Lose</h2>";
        }
        else if($status == "Win"){
            echo "<h2 style='color: green'>You Won</h2>";
        }
        else echo "<h2 style='color: blue'>It's a Tie</h2>";
    }
    
    if(isset($_POST["logout"])){
        $_SESSION['username'] = NULL;
        header('Location: login.php');
        exit();
    }
}

// getting top users
$topUsers = getTopUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>

    <!-- Rock Paper Scissors game form -->
    <form method="post">
        <label for="user_choice">Choose: </label>
        <select name="user_choice" id="user_choice">
            <option value="rock">Rock</option>
            <option value="paper">Paper</option>
            <option value="scissors">Scissors</option>
        </select>
        <button type="submit" name="play">Play</button>
    </form>


    <h3>Your Score: <?php echo $user['score']; ?></h3>

    <h3>Top 3 Users:</h3>
    <ul>
        <?php foreach ($topUsers as $topUser): ?>
            <li><?php echo $topUser['username'] . ' - ' . $topUser['score']; ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- LogOut -->
    <form method="post">
        <button type="submit" name="logout">LogOut</button>
    </form>
    
</body>
</html>
