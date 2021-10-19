<?php

namespace Liushoukun\LaravelHelpers\Helpers\Data;

class InputHelper
{

    public static function toArray($data)
    {
        if (blank($data)) {
            return [];
        }
        if (is_string($data)) {
            try {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $throwable) {
                $data = [];
            }
            return $data;
        }
        if (is_array($data)) {
            return $data;
        }
        return (array)$data;
    }

    public static function toJson($data)
    {

        if (blank($data)) {
            return '';
        }


        if (is_array($data)) {
            return json_encode($data);
        }
        $data = (string)$data;
        try {
            $data = json_decode($data, true);
            if(blank($data)){
                return  '';
            }
            $data = json_encode($data);
            return $data;
        } catch (\Throwable $throwable) {
            return '';
        }

    }

}
