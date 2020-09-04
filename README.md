
Sudoku game base on websockets
------------

The minimum requirement by this project template that your Web server supports PHP 5.6.0.


INSTALLATION
------------

### Install via Composer

~~~
composer install
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
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

