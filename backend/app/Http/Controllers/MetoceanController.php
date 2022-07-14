<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Extensions\ParseTextData;
use App\Model\DataField;
use App\Model\DataValue;

class MetoceanController extends Controller
{
    /**
     * Response metocean data
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getField(Request $request)
    {
       
        $result = DataField::all();

        return $this->sendResponse($result, "Successfully.");
    }

    /**
     * Response metocean data
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getValue(Request $request)
    {
       
        $result = [];
        $fieldList = DataField::all();
        $keys = DataValue::select()->groupBy('date_time')->orderBy('date_time', 'asc')->get()->toArray();
        $chartData = [];
        foreach ($fieldList as $item ) {
          $fields[] = $item['name'];
          $temp = DataValue::select()->where('field_id', $item['id'])->orderBy('date_time', 'asc')->get()->toArray();
          $chartData[$item['name']] = array_column($temp, 'value');
        }
       
        $data = [];
        $result['key'] = array_column($keys, 'date_time');
        $result['fields'] = $fields;
        $result['data'] = $data;
        $result['chartData'] = $chartData;

        return $this->sendResponse($result, "Successfully.");
    }
}
