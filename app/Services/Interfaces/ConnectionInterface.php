<?php

namespace App\Services\Interfaces;

/**
 * Interface ConnectionInterface
 * @package App\Services\Interfaces
 */
interface ConnectionInterface
{
    /**
     * @param string $url
     * @param string $method
     * @param string $parameters
     *
     * @return mixed
     */
    public function send(string $url, string $method, $parameters = '');
}