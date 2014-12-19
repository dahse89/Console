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
Console::write("Input: ");
$line = Console::readLine();
// OR
$line = Console::readLine("Input: ");
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
process visualisation
```php
/* calc PI */
Console::writeLine("Calculate PI!",Color::Yellow,Style::Intensity);
$accuracy = Console::readInt("Accuracy (enter Integer e.g 10000000 -> Accuracy of 7 decimal places): ");
$pi = 0;
Console::startProcess("calculating", $accuracy);
for( $i = 1; $i < $accuracy; $i++) {
    $pi += 4 / ($i * 2 - 1) * ($i & 1 ? 1 : -1);
    Console::writeProcess($i);
}
Console::writeProcessEnd();
Console::writeLine($pi);

//> Calculate PI!
//> Accuracy (enter Integer e.g 10000000 -> Accuracy of 7 decimal places): xas     
//> Please enter a number!
//> Accuracy (enter Integer e.g 10000000 -> Accuracy of 7 decimal places): 10000
//> calculating: 100%   <- running from 0 to 100%
//> 3.1416926635905

```
