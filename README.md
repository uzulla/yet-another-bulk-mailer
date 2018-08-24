秘伝のバルクメーラー
===============


# use local php env

## setup

```
$ composer install
```

composerはたとえば以下のようにして入れてね。

```
$ wget -O composer-setup.php https://getcomposer.org/installer
$ php composer-setup.php
$ php composer.phar help
$ rm composer-setup.php

$ php composer.phar install
```

## start built-in web server

```
$ php -S 127.0.0.1:8080

and open http://127.0.0.1:8080/
```


# use Docker

```
$ docker build -t yet-another-bulk-mailer .
$ docker run -p 8080:8080 yet-another-bulk-mailer
```

