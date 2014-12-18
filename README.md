Console
=======

PHP Class for easy build CLI scripts.

exit if script isn't started in cli
```php
Console::exitInWrongEnv("Please run in shell only");
```
Print memory usage, pretty
```php
for($i = 0; $i < 10; $i++){
    $buffer[] = str_repeat(md5(uniqid()),0xfff);
    echo Console::getMemory(true)." ";
}
//512kB 768kB 1MB 1.25MB 1.5MB 1.75MB 2MB 2.25MB 2.5MB 2.75MB
```
reading cli inputs
```php
echo "Input: ";
$line = Console::readLine();
```
