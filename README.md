
Sudoku game based on websockets
------------

Реализован бэкэнд с WebSockets с методами: начало новой игры, добавить результат, топ игроков (хранить результаты можно в кэше сервера).
Должен быть простой фронт с полями под игру, полем для имени, кнопками начать и просмотр топ. 

Логика:

Несколько вкладок играют в конкурентное судоку, то есть одна текущая игра на всех. 
Каждый имеет право поставить в свободную ячейку. 
Кто первый поставит последнюю цифру и судоку посчитается правильно, тот и победил. 
Любая цифра, поставленная на поле, должна отобразиться у других без возможности изменения.



INSTALLATION
------------

### Install via Composer

~~~
composer install
~~~

Now you should be able to access the application through the following URL, assuming `sudoku` is the directory
directly under the Web root.

~~~
http://localhost/sudoku/web/
~~~


CONFIGURATION
-------------

### REDIS

Edit the file `config/console.php` with real data, for example:

```php
'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
```



### Running Web socket

 
1. Run `php yii web-sockets/sudoku-game`

