<?php

namespace zhivaevvd\guessNumber\Model;

use function zhivaevvd\guessNumber\View\greeting;
use function zhivaevvd\guessNumber\View\endGame;
use function zhivaevvd\guessNumber\View\MenuGame;
use function zhivaevvd\guessNumber\DataBase\insertNewGame;
use function zhivaevvd\guessNumber\DataBase\addAttemptInDB;
use function zhivaevvd\guessNumber\DataBase\outputListGame;
use function zhivaevvd\guessNumber\DataBase\updateInfoGame;
use function zhivaevvd\guessNumber\DataBase\outputListGameTop;
use function zhivaevvd\guessNumber\DataBase\checkGameid;

function setting()
{
    define("MAX_NUMBER", 10);
    define("NUMBER_ATTEMPT", 3);
}

function showGame($user_name)
{
    $hidden_num = mt_rand(1, MAX_NUMBER);
    echo "Попробуйте угадать." . PHP_EOL;

    $attempt = 1;

    $idNewGame = insertNewGame($user_name, $hidden_num, MAX_NUMBER);

    while ($attempt <= NUMBER_ATTEMPT) {
        $get_num = readline();

        while (is_numeric($get_num) === false) {
            echo "Введено не число! " . PHP_EOL;
            $get_num = readline();
        }

        if ($get_num == $hidden_num) {
            addAttemptInDB($idNewGame, $get_num, "guessed", $attempt);
            updateInfoGame($idNewGame, "win");
            endGame($hidden_num, $attempt);
            break;
        }

        if ($get_num < $hidden_num) {
            echo 'Твое число слишком маленькое' . PHP_EOL;
            addAttemptInDB($idNewGame, $get_num, "number is small", $attempt);
        } elseif ($get_num > $hidden_num) {
            echo 'Твое число слишком большое' . PHP_EOL;
            addAttemptInDB($idNewGame, $get_num, "number is large", $attempt);
        }

        $attempt++;
    }

    if ($attempt > NUMBER_ATTEMPT) {
        updateInfoGame($idNewGame, "loss");
        endGame($hidden_num);
    }
}

function replayGame($user_name)
{
    echo $user_name . ', попробуем еще раз? (y ="Да" / n = "Нет")' . PHP_EOL;
    echo 'Хотите закончить? (--exit - Выход из игры | --menu - Меню игры)' . PHP_EOL;
    $replay_game = readline();

    if ($replay_game === 'y' || $replay_game === 'Y') {
        showGame($user_name);
    } elseif ($replay_game === 'n' || $replay_game === 'N') {
        echo 'Эх,жалко ' . $user_name . '. До свидания!' . PHP_EOL;
    } elseif ($replay_game === '--exit') {
        exit();
    } elseif ($replay_game === '--menu') {
        MenuGame();
    } else {
        replayGame($user_name);
    }
}

function commandHandler($getCommand)
{
    $checkCommand = false;

    while ($checkCommand === false) {
        if ($getCommand === "--new") {
            greeting();

            $checkCommand = true;
        } elseif ($getCommand === "--list") {
            outputListGame();
        } elseif ($getCommand === "--list win") {
            outputListGame("win");
        } elseif ($getCommand === "--list loose") {
            outputListGame("loss");
        } elseif ($getCommand === "--top") {
            outputListGameTop();
        } elseif (preg_match('/(^--replay [0-9]+$)/', $getCommand) != 0) {
            $temp = explode(' ', $getCommand);
            $id = $temp[1];

            unset($temp);

            $checkId = checkGameid($id);

            if ($checkId) {
                showGame($checkId);
            } else {
                echo "Такой игры не существует" . PHP_EOL;
            }
        } elseif ($getCommand === "--exit") {
            exit;
        }

        $getCommand = \cli\prompt("Введите ключ");
    }
}

?>