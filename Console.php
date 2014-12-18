<?php
/**
 * Created by PhpStorm.
 * User: pdahse
 * Date: 18.12.14
 * Time: 14:48
 */

class Console {
    const STDIN = "php://stdin";

    public static function prettyMemory($value){
        foreach(array('B','kB','MB','GB','TB') as $x => $label){
            if($value < pow(0x400,$x+1)){
                return round($value/ pow(0x400,$x),2) . $label;
            }
        }
    }

    public static function writeLine($str = ""){
        self::write($str.PHP_EOL);
    }

    public static function write($str){
        echo $str;
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

    public static function isRunningInCLI(){
        return PHP_SAPI === 'cli';
    }

    public static function exitInWrongEnv($message = ""){
        if(!self::isRunningInCLI()) die($message);
    }
}