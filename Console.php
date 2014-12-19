<?php
/**
 * Created by PhpStorm.
 * User: pdahse
 * Date: 18.12.14
 * Time: 14:48
 */

class Console {
    const STDIN = "php://stdin";
    private static $lastProcessValLength;
    private static $processlabel;
    private static $processTotal;

    private function __construct(){}

    public static function prettyMemory($value){
        foreach(array('B','kB','MB','GB','TB') as $x => $label){
            if($value < pow(0x400,$x+1)){
                return round($value/ pow(0x400,$x),2) . $label;
            }
        }
    }

    public static function write($str = "", $color = Color::Reset, $style = Style::None){
        $colorstyle = self::createStyle($color,$style);
        echo $colorstyle.$str.self::createStyle();
    }


    private static function createStyle($color = -1, $style = -1){
        if( $style === Style::None ||
            $style === Style::Bold ||
            $style === Style::Underline){
            return "\033[$style;{$color}m";
        }elseif($style === Style::Background){
            $color = $color + 10;
            return "\033[{$color}m";
        }elseif($style === Style::Intensity){
            $color = $color + 60;
            return "\033[0;{$color}m";
        }elseif($style === Style::Bold_Intensity){
            return "\033[1;{$color}m";
        }elseif($style === Style::Background_Intensity){
            $color = $color + 70;
            return "\033[0;{$color}m";
        }
        return "\033[0m";

    }

    public static function writeLine($str = "",  $color = Color::Reset, $style = Style::None){
        $colorstyle = self::createStyle($color,$style);
        echo $colorstyle.$str.self::createStyle().PHP_EOL;
    }



    public static function writeUnicode($unicode){

        if(is_array($unicode)){
            if(count($unicode) === 1){
                return self::writeUnicode($unicode[0]);
            }else{
                self::writeUnicode(array_shift($unicode));
                return self::writeUnicode($unicode);
            }
        }
        self::write((is_int($unicode) ? json_decode('"\u'. dechex($unicode).'"'): $unicode));
    }

    public static function getMemory($pretty = false){
        $memory = memory_get_usage(true);
        return $pretty ? self::prettyMemory($memory) : $memory;
    }

    public static function readLine($question = ""){
        self::write($question);
        return trim(fgets(fopen(self::STDIN,"r")));
    }

    public static function readInt($question = "") {
        while(!is_numeric($int = self::readLine($question))){
            self::writeLine("Please enter a number!",Color::Red);
        }
        return (int) filter_var($int,FILTER_SANITIZE_NUMBER_INT);
    }

    public static function isRunningInCLI(){
        return PHP_SAPI === 'cli';
    }

    public static function exitInWrongEnv($message = ""){
        if(!self::isRunningInCLI()) die($message);
    }

    public static function cursorUp($x){
        self::write("\033[{$x}A");
    }

    public static function cursorDown($x){
        self::write("\033[{$x}B");
    }

    public static function cursorForward($x){
        self::write("\033[{$x}C");
    }

    public static function cursorBackward($x){
        self::write("\033[{$x}D");
    }

    public static function cursorSave(){
        self::write("\033[s");
    }

    public static function cursorRestore(){
        self::write("\033[u");
    }

    public static function startProcess($label,$total){
        self::$processlabel = $label;
        self::$processTotal = $total;
        self::cursorHide();
    }

    public static function writeProcess($curr){
        $percentage = 100/self::$processTotal * $curr;
        $val = round($percentage,2)."%";
        $str = self::$processlabel.": ";
        $vallength = strlen($val);
        $length = strlen($str) + $vallength;

        $clear = 0;
        if($length < self::$lastProcessValLength){
            $clear = self::$lastProcessValLength - $length;
        }
        self::$lastProcessValLength = $length;
        self::write($str);
        if($val < 16.5){
            $color = Color::Red;
            $style = Style::Intensity;
        }elseif($val >= 16.5 && $val < 33){
            $color = Color::Red;
            $style = Style::None;
        }elseif($val >= 33 && $val < 50){
            $color = Color::Yellow;
            $style = Style::Intensity;
        }elseif($val >= 50 && $val < 66.5){
            $color = Color::Yellow;
            $style = Style::None;
        }elseif($val >= 66.5 && $val < 83){
            $color = Color::Green;
            $style = Style::None;
        }else{
            $color = Color::Green;
            $style = Style::Intensity;
        }
        self::write($val,$color,$style);
        self::write(str_repeat(" ",$clear));
        self::cursorBackward($length + $clear);
    }

    public static function writeProcessEnd(){
        self::write(self::$processlabel.": ");
        self::writeLine("100%   ",Color::Green,Style::Bold_Intensity);
        self::cursorShow();
    }
    public static function cursorHide(){
        passthru("tput civis");
    }
    public static function cursorShow(){
        passthru("tput cnorm");
    }
}
class Color{
    const Black = 30;
    const Red = 31;
    const Green = 32;
    const Yellow = 33;
    const Blue = 34;
    const Purple = 35;
    const Cyan = 36;
    const White = 37;
    const Reset = 0;
}

class Style{
    const None = 0;
    const Bold = 1;
    const Underline = 4;
    const Background = 8;
    const Intensity = 16;
    const Bold_Intensity = 32;
    const Background_Intensity = 64;
}