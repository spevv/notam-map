<?php

namespace App\Services;

use App\Services\Interfaces\ConvertDMSInterface;

/**
 * Class DMSService
 * @package App\Services
 */
class DMSService implements ConvertDMSInterface
{
    /**
     * Get latLng by DMS
     *
     * @param string $string
     *
     * @return array
     */
    public function getLatLngByDMS(string $string)
    {
        $dms = $this->parseDMS($string);

        return [
            'lat' => $this->DMSToDecimal($this->convertDMS($dms[1])),
            'lng' => $this->DMSToDecimal($this->convertDMS($dms[2])),
        ];
    }

    /**
     * Parse DMS from string
     *
     * @param string $string
     *
     * @return mixed
     * @throws \Exception
     */
    protected function parseDMS(string $string)
    {
        if (preg_match('/(\d*[NS])(\d*[WE])/', strtoupper($string), $dms)) {
            return $dms;
        }

        throw new \Exception('Can not parse DMS.');
    }

    /**
     * Convert DMS to decimal
     *
     * @param array $data
     *
     * @return bool|float|int|string
     */
    protected function DMSToDecimal(array $data)
    {
        if (!is_numeric($data['degrees']) || $data['degrees'] < 0 || $data['degrees'] > 180) {
            $decimal = false;
        } elseif (!is_numeric($data['minutes']) || $data['minutes'] < 0 || $data['minutes'] > 59) {
            $decimal = false;
        } elseif (!is_numeric($data['seconds']) || $data['seconds'] < 0 || $data['seconds'] > 59) {
            $decimal = false;
        } else {
            $decimal = $data['degrees'] + ($data['minutes'] / 60) + ($data['seconds'] / 3600);
            if ($data['direction'] == 'S' || $data['direction'] == 'W') {
                $decimal *= -1;
            }
        }

        return $decimal;
    }

    /**
     * Convert DMS string to array with keys
     *
     * @param string $string
     *
     * @return array
     */
    protected function convertDMS(string $string)
    {
        $array = str_split(trim($string));
        $data = [
            'direction' => array_pop($array),
            'degrees' => '',
            'minutes' => '',
            'seconds' => '',
        ];
        foreach ($array as $key => $value) {
            if (2 > strlen($data['degrees'])) {
                $data['degrees'] .= $value;
            } elseif (2 > strlen($data['minutes'])) {
                $data['minutes'] .= $value;
            } else {
                $data['seconds'] .= $value;
            }
        }
        $data['degrees'] = $data['degrees'] ?: 0;
        $data['minutes'] = $data['minutes'] ?: 0;
        $data['seconds'] = $data['seconds'] ?: 0;

        return $data;
    }
}
