<?php
class Logger
{
    const FILENAME = 'error.log';

    static public function log($str = '')
    {
        $date = new DateTime();
        file_put_contents(
            self::FILENAME,
            $date->format('Y-m-d H:i:s').' '.$str.PHP_EOL,
            FILE_APPEND);
    }
}