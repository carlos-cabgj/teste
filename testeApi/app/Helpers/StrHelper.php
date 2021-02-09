<?php

namespace App\Helpers;

class StrHelper
{
    public static function filterPhone($dado)
    {
        return preg_replace('/[^0-9]/', '', $dado);
    }

    public static function filterCpfNis($dado, $limit = 11)
    {
        $clean = preg_replace('/[^0-9]/', '', $dado);
        if (empty($clean)) {
            return '';
        }
        return str_pad(substr((string)$clean, 0, $limit), $limit, '0', STR_PAD_LEFT);
    }
}
