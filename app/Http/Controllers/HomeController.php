<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $jsonString = file_get_contents(base_path('resources/data/data.json'));
        $data = $this->generateData($jsonString);

        return view('home', $data);
    }

    public function generateData($jsonString)
    {
        $data = json_decode($jsonString, true);
        $items = $data['metaData']['items'];
        $dimensions = $data['metaData']['dimensions'];
        $rows = $data['rows'];
        $locations = $dimensions['ou'];
        $dxValues = $dimensions['dx'];
        $periods = $dimensions['pe'];
        //dd($rows);
        $dxDataWithYears = [];

        foreach ($dxValues as $value) {
            //foreach ($periods as $period) {
            array_push($dxDataWithYears, [
                'name' => $this->extractItem($items, $value)['name'],
                'uuid' => $value,
                'periods' => $periods
            ]);
            //}
        }
        //dd($dxDataWithYears);
        $locationsWithCovarage = [];

        foreach ($locations as $location) {
            foreach ($dxDataWithYears as $key => $dx) {
                $values = [];

                foreach ($dx['periods'] as $period) {
                    array_push($values, [$this->extractValue($rows, $location, $dx['uuid'], $period)]);
                }
                //dd($values);
                $dxDataWithYears[$key]['values'] = $values;
            }

            array_push($locationsWithCovarage, [
                'name' => $this->extractItem($items, $location)['name'],
                'uuid' => $location,
                'dxDataWithYears' => $dxDataWithYears
            ]);
        }

        foreach ($periods as $key => $period) {
            $periods[$key] = $this->extractItem($items, $period)['name'];
        }
        //dd($locationsWithCovarage);
        return ['data' => $locationsWithCovarage, 'headers' => $periods];
        //dd($periods);
        
        //dd($this->extractItem($items, "sB79w2hiLp8"));
        /*     foreach ($locationsWithCovarage as $ou => $key) {
        }
        
 */
        dd($this->extractValue($rows, "O6uvpzGd5pu", "Uvn6LCg7dVU", "202108"));
    }

    private function extractItem($items, $itemId)
    {
        return $items[$itemId];
    }

    private function extractValue($rows, $ou, $dx, $pe)
    {
        foreach ($rows as $row) {
            $rowDx = $row[0];
            $rowPe  = $row[1];
            $rowOu = $row[2];
            $rowValue = $row[3];

            //dd($rowDx, $rowOu, $rowPe);
            if ($rowDx == $dx && $rowOu == $ou && $rowPe == $pe) {
                return $rowValue;
            }
        }
    }
}
