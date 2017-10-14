<?php

namespace App\Services\Interfaces;

/**
 * Interface ConvertDMSInterface
 * @package App\Services\Interfaces
 */
interface ConvertDMSInterface
{
    /**
     * @param string $string
     *
     * @return mixed
     */
    public function getLatLngByDMS(string $string);
}