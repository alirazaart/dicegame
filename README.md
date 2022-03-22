# Dice Roller Game

## Description
This is a Dice App Application using Laravel 8.
Dice Game script where it can receive input N which is the number of players and input M which is the number of dice for each player. These are the game rules:

* 1.   At the beginning of the game, each player would receive an M unit dice..
* 2.   Each player would throw their dice at the same time.
* 3.   Each player then would check their own dice results, and make these evaluations:
    * a.   Dice with 6 number would be removed from the game. The player would receive 1 victory point.
    * b.   Dice with 1 number would be given to their neighbor player.
    * c.   Dice with 2,3,4 and 5 numbers would be kept for the next round.
* 4.   After evaluation, the player who has no dice should not play anymore. They also cannot receive dice after this round.
* 5.   If at the end of evaluation only one player has dice, the game would end.
* 6.   Players with the biggest victory point win the game. In case of tie, both players win the game.

## Requirement
* [PHP](http://php.net/supported-versions.php) >= 7.3
* [Composer](https://getcomposer.org/)
* Apache 2+

## Installing
- Create a new project folder, eg: dice
- cd dice
- git clone https://github.com/alirazaart/dicegame.git
- Add virtual host in apache, eg: dice.com
- Edit your host file: add 127.0.0.1 dice.com
- Install dependencies
```
composer install
```


<!-- ## Demo -->
<!-- [Demo](https://laradice.herokuapp.com) -->

## Develop By
[Ali Raza](http://aliraza-chandio.github.io)




