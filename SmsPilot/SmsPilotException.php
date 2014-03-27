<?php

namespace SmsPilot;


class SmsPilotException extends \Exception {

    static public $errorInfo = array(
        '10' => 'INPUT data is required',
        '11' => 'Unknown INPUT format',
        '12' => 'XML structure is invalid',
        '13' => 'JSON structure is invalid',
        '14' => 'Unknown COMMAND',
        '100' => 'APIKEY is required',
        '101' => 'APIKEY is invalid',
        '106' => 'APIKEY is blocked (spam)',
        '110' => 'SYSTEM ERROR',
        '113' => 'IP RESTRICTION',
        '201' => 'FROM is invalid',
        '202' => 'FROM is depricated',
        '204' => 'FROM not found',
        '210' => 'TO is required',
        '211' => 'TO is invalid',
        '212' => 'TO is depricated',
        '213' => 'Unsupported zone',
        '220' => 'TEXT is required',
        '221' => 'TEXT too long',
        '230' => 'ID is invalid',
        '231' => 'PACKET_ID is invalid',
        '240' => 'Invalid INPUT list',
        '241' => 'You don\'t have enough money',
        '242' => 'SMS count limit (trial)',
        '243' => 'Loop protection',
        '250' => 'SEND_DATETIME is invalid',
        '260' => 'Invalid callback URL',
        '270' => 'Invalid ttl',
        '302' => 'SMS server_id not found',
        '303' => 'Invalid SMS check list',
        '304' => 'SERVER_PACKET_ID is invalid',
        '400' => 'User not found',
        '401' => 'Invalid login details',
        '500' => 'Invalid -since- format, right is YYYY-MM-DD HH:II:SS',
        '600' => 'Expired',
        '601' => 'Undeliverable',
        '602' => 'Destination unreachable',
        '603' => 'Rejected',
    );

    static public function getErrorInfo($code)
    {
        return isset(self::$errorInfo[$code]) ? self::$errorInfo[$code] : false;
    }



}