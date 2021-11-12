<?php

namespace zhivaevvd\guessNumber\DataBase;

use SQLite3;

use function zhivaevvd\guessNumber\View\outputGamesInfo;
use function zhivaevvd\guessNumber\View\outputTurnInfo;
use function zhivaevvd\guessNumber\View\outputGamesInfoTop;

function createDatabase()
{
    $db = new \SQLite3('gameGuessNumber.db');

    $gamesInfoTable = "CREATE TABLE gamesInfo(
        idGame INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        gameData DATE,
        gameTime TIME,
        playerName TEXT,
        maxNumber INTEGER,
		generatedNumber INTEGER,
		gameOutcome TEXT
	 )";
    $db->exec($gamesInfoTable);


    $attemptsTable = "CREATE TABLE attempts(
		 idGame INTEGER,
		 numberAttempts INTEGER,
		 proposedNumber INTEGER,
		 computerResponds TEXT
	 )";
    $db->exec($attemptsTable);
}

function openDatabase()
{
    if (!file_exists("gameGuessNumber.db")) {
        createDatabase();
    } else {
        $db = new \SQLite3('gameGuessNumber.db');
    }
}

function insertNewGame($user_name, $hidden_num, $MAX_NUM)
{
    $db = new \SQLite3('gameGuessNumber.db');

    date_default_timezone_set("Europe/Moscow");

    $gameData = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");

    $query = "INSERT INTO gamesInfo(
		gameData,
		gameTime,
		playerName,
		maxNumber,
		generatedNumber
   ) VALUES(
		'$gameData',
      '$gameTime',
		'$user_name',
		'$MAX_NUM',
		'$hidden_num'
   )";

    $db->exec($query);

    $query = "SELECT idGame FROM gamesInfo ORDER BY idGame DESC LIMIT 1";

    return $db->querySingle($query);
}

function addAttemptInDB($idGame, $proposedNumber, $computerResponds, $numberAttempts)
{
    $db = new \SQLite3('gameGuessNumber.db');

    $query = "INSERT INTO attempts(
	    idGame,
	    numberAttempts,
		proposedNumber,
		computerResponds
    ) VALUES(
        '$idGame',
        '$numberAttempts',
        '$proposedNumber',
        '$computerResponds'
    )";

    $db->exec($query);
}

function updateInfoGame($idGame, $gameOutcome)
{
    $db = new \SQLite3('gameGuessNumber.db');

    $query = "UPDATE gamesInfo SET gameOutcome = '$gameOutcome' WHERE idGame = '$idGame'";

    $db->exec($query);
}

function outputListGame($gameOutcome = false)
{
    $db = new \SQLite3('gameGuessNumber.db');

    if ($gameOutcome === "win") {
        $result = $db->query("SELECT * FROM gamesInfo WHERE gameOutcome = '$gameOutcome'");
    } elseif ($gameOutcome === "loss") {
        $result = $db->query("SELECT * FROM gamesInfo WHERE gameOutcome = '$gameOutcome'");
    } else {
        $result = $db->query("SELECT * FROM gamesInfo");
    }

    while ($row = $result->fetchArray()) {
        outputGamesInfo($row);

        $query = "SELECT
            numberAttempts,
            proposedNumber, 
            computerResponds
            FROM attempts 
            WHERE idGame='$row[0]'
            ";

        $gameTurns = $db->query($query);
        while ($gameTurnsRow = $gameTurns->fetchArray()) {
            outputTurnInfo($gameTurnsRow);
        }
    }
}

function outputListGameTop()
{
    $db = new \SQLite3('gameGuessNumber.db');

    $result = $db->query("SELECT playerName, 
    (SELECT COUNT(*) FROM gamesInfo as b WHERE a.playerName = b.playerName AND gameOutcome = 'win') as countWin,
    (SELECT COUNT(*) FROM gamesInfo as c WHERE a.playerName = c.playerName AND gameOutcome = 'loss') 
    as countLoss FROM gamesInfo as a
    GROUP BY playerName ORDER BY countWin DESC, countLoss");

    while ($row = $result->fetchArray()) {
        outputGamesInfoTop($row);
    }
}

function checkGameId($id)
{
    $db = new \SQLite3('gameGuessNumber.db');

    $query = "SELECT playerName FROM gamesInfo WHERE idGame=" . $id;

    if ($db->querySingle($query)) {
        return $db->querySingle($query);
    }

    return false;
}

?>