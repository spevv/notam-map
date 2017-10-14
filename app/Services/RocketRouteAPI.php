<?php

namespace App\Services;

use App\Services\Interfaces\ConnectionInterface;
use App\Services\Interfaces\ConvertDMSInterface;

/**
 * Class RocketRouteAPI
 * @package App\Services
 */
class RocketRouteAPI
{
    /**
     * @var string url
     */
    protected $url = 'https://apidev.rocketroute.com/';

    /**
     * @var ConnectionInterface
     */
    private $connectionService;

    /**
     * @var ConvertDMSInterface
     */
    private $convertDMS;

    /**
     * RocketRouteAPI constructor.
     *
     * @param ConnectionInterface $connectionService
     * @param ConvertDMSInterface $convertDMS
     */
    public function __construct(ConnectionInterface $connectionService, ConvertDMSInterface $convertDMS)
    {
        $this->connectionService = $connectionService;
        $this->convertDMS = $convertDMS;
    }

    /**
     * Get notam (lat, lng, content)
     *
     * @param string $icao
     *
     * @return array
     * @throws \Exception
     */
    public function getNotam(string $icao)
    {
        $points = [];
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<REQWX>
  <USR>%s</USR>
  <PASSWD>%s</PASSWD>
  <KEY>%s</KEY>
  <ICAO>%s</ICAO>
</REQWX>
XML;

        $data = $this->connectionService->send(
            $this->url . 'notam/v1/service.wsdl',
            'getNotam',
            sprintf(
                $xml,
                config('app.rocket_user'),
                config('app.rocket_password'),
                config('sms.rocket_md5'),
                $icao
            )
        );

        $NOTAMs = $data->NOTAMSET->NOTAM;
        if (isset($NOTAMs)) {
            foreach ($NOTAMs as $NOTAM) {
                $dmsData = $this->convertDMS->getLatLngByDMS($NOTAM->ItemQ);
                $points[] = [
                    'lat' => $dmsData['lat'],
                    'lng' => $dmsData['lng'],
                    'content' => (string)$NOTAM->ItemE,
                ];
            }
        }

        if (empty($points)) {

            throw new \Exception('Can not find any data.');
        }

        return $points;
    }
}
