<?php 
namespace zhivaevvd\guessNumber\Controller;

use function zhivaevvd\guessNumber\Model\setting;
use function zhivaevvd\guessNumber\View\MenuGame;
use function zhivaevvd\guessNumber\DataBase\openDatabase;

function startGame()
{
    setting();
    openDatabase();
    MenuGame();
}

?> 