Console
=======

PHP Class for easy build CLI scripts.

**basic utilities**

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
writing unicode
```php
Console::writeUnicode(0x2727);
Console::writeUnicode(0x269D);
Console::writeUnicode(0x2603);
// ✧ ⚝ ☃

Console::writeUnicode([
    PHP_EOL, 'a', 0x250C, 0x2500, 0x2510,'b',
    PHP_EOL, 'c',0x85, 0x2514, 0x2500, 0x2518,'d'
]);
//  a┌─┐b
//  c└─┘d
```
