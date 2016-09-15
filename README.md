# meteor-ddp-php

A [minimalist](http://www.becomingminimalist.com/) PHP library that implements DDP client, the realtime protocol for [Meteor](https://www.meteor.com/ddp) framework.

[![Join the chat at https://gitter.im/synchrotalk/meteor-ddp-php](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/synchrotalk/meteor-ddp-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)


### How to use

Suppose you have declared a remote function `foo` in your meteor server code :
```javascript
Meteor.methods({
  foo : function (arg) {
    check(arg, Number);
    if (arg == 1) { return 42; }
    return "You suck";
  }
});
```

Then in your php client's code, you could just invoke `foo` by executing :
```php
use synchrotalk\MeteorDDP\DDPClient;

$client = new DDPClient('localhost', 3000);

$client->connect();

$client->call("foo", array(1));
while(($a = $client->getResult("foo")) === null) {};

echo 'Result = ' . $a . PHP_EOL;

$client->stop();
```

===>
```
Result = 42
```

More use cases can be found in the [examples](https://github.com/synchrotalk/meteor-ddp-php/tree/devel/examples) folder.
### How to install
   This library is available via [composer](https://packagist.org/packages/synchrotalk/meteor-ddp-php), the dependency manager for PHP. Please add this in your composer.json :
```php
"require" : {
    "synchrotalk/meteor-ddp-php": "0.1.0"
}
```
  and update composer to automatically retrieve the package :
```shell
php composer.phar update
```
### Roadmap
  None

### Version
0.1.0
