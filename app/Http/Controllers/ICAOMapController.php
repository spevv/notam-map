<?php

namespace App\Http\Controllers;

use App\Services\DMSService;
use App\Services\RocketRouteAPI;
use App\Services\SOAPService;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

/**
 * Class ICAOMapController
 * @package App\Http\Controllers
 */
class ICAOMapController extends Controller
{
    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * @var RocketRouteAPI
     */
    private $rocketRouteAPI;

    /**
     * ICAOMapController constructor.
     *
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
        $this->rocketRouteAPI = new RocketRouteAPI(new SOAPService, new DMSService);
    }

    /**
     * Display ICAO map
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response->view('icao-map', [
            'icao' => '',
            'points' => json_encode([]),
        ]);
    }

    /**
     * Find and output ICAO
     * POST
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            'icao' => 'required|string|max:4|min:4',
        ]);
        $icao = $request->get('icao');

        try {
            return $this->response->view('icao-map',
                [
                    'icao' => $icao,
                    'points' => json_encode($this->rocketRouteAPI->getNotam($icao)),
                ]);
        } catch (\Exception $exception) {

            return $this->response->redirectToRoute('index')->withInput()->withErrors(['icao' => [$exception->getMessage()]]);
        }
    }
}

