<?php

namespace Core\Application\Service;

use stdClass;

class UtilService
{
    function convertArrayToObject($array): stdClass
    {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            $object->$key = $value;
        }
        return $object;
    }

}
