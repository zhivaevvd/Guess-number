<?php namespace zhivaevvd\guessNumber\Controller;
    use function zhivaevvd\guessNumber\View\showGame;

    function startGame() {
        echo "Game started".PHP_EOL;
        showGame();
    }
?> 