<?php namespace zhivaevvd\guessNumber\View;
use function zhivaevvd\guessNumber\Controller\showGame;
use function zhivaevvd\guessNumber\Controller\settings;
use function zhivaevvd\guessNumber\Controller\greetings;
    
function startGame() {
    settings();
    greetings();
    showGame();
}

function showList() {
    echo "Вывод списка всех сохраненных игр из БД SQLite3\n";
}

function showReplay() {
    echo " Повтор всех ходов игры с идентификатором id\n";
}
function showTop() {
    echo " Вывод статистики по игрокам из БД SQLite3\n";
}
?> 