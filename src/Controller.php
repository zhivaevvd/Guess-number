<?php namespace zhivaevvd\guessNumber\Controller;
    use function zhivaevvd\guessNumber\View\startGame;
    use function zhivaevvd\guessNumber\View\showList;
    use function zhivaevvd\guessNumber\View\showTop;
    use function zhivaevvd\guessNumber\View\showReplay;

    function func() {
        $key = readline("Введите ключ: ");
    if ($key == "--new") {
        startGame();
    } elseif ($key == "--list") {
        showList();
    } elseif ($key == "--top") {
        showTop();
    } elseif ($key == "--replay") {
        showReplay();
    } else {
        echo "Неверный ключ.\n";
        func();
    }
}

function settings() {
    define("MAX_NUMBER", 10);
    define("NUMBER_ATTEMPT", 3);
}

function greetings() {
    global $user_name;
    echo 'Здравствуйте! Ваше имя?' . PHP_EOL;
    $user_name = readline();
    echo 'Отлично, ' . $user_name . '!' . PHP_EOL . 'Сейчас мы сыграем в игру "Угадай число".'
    . ' Я загадываю число от 1 до ' . MAX_NUMBER . ' и вы должны отгадать число за ' . NUMBER_ATTEMPT . ' попытки.'
    . PHP_EOL;
}

function showGame() {
    $hidden_num = rand(1, MAX_NUMBER);
    echo "Попробуйте заново." . PHP_EOL;

    $attempt = 1;
    while ($attempt <= NUMBER_ATTEMPT) {
        $get_num = readline();

        if ($get_num == $hidden_num) {
            endGame($hidden_num, $attempt);
            break;
        } elseif ($get_num < $hidden_num) {
            echo 'Загаданное число больше' . PHP_EOL;
        } elseif ($get_num > $hidden_num) {
            echo 'Загаданное число меньше' . PHP_EOL;
        }

        $attempt++;
    }

    if ($attempt > NUMBER_ATTEMPT) {
        endGame($hidden_num);
    }
}

function endGame($hidden_num, $attempt = false) {
    if ($attempt) {
        echo 'Поздравляю! Вы выиграли игру за ' . $attempt . ' попытки.' . PHP_EOL;
        replayGame();
    } else {
        echo 'Вы проиграли. Я загадал число: ' . $hidden_num . PHP_EOL;
        replayGame();
    }
}

function replayGame() {
    global $user_name;

    echo $user_name . ', попробуем еще раз? (y ="Да" / n = "Нет")' . PHP_EOL;
    $replay_game = readline();

    if ($replay_game == 'y' || $replay_game == 'Y') {
        showGame();
    } elseif ($replay_game == 'n' || $replay_game == 'N') {
        echo 'Эх,жалко ' . $user_name . '. Заходите еще.' . PHP_EOL;
    } else {
        replayGame();
    }
}
?> 