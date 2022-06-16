<?php

namespace App\Helpers;

use App\Error\ApiException;

class Json
{
    public static function decode(string $json):array
    {
        $data = json_decode($json, true);

        if(!is_array($data)) {
            throw new ApiException(400, 'Payload is not parsable as JSON array');
        }

        return $data;
    }

    public static function encode(array $data): string
    {
        $json = json_encode($data);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ApiException(400, 'Could not convert data as valid json');
        }

        return $json;
    }
}