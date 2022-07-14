<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Model\DataField;
use App\Model\DataValue;
use Carbon\Carbon;


class storeParseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:store-parse-data {fileName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse the file and store the data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $fileName = $this->argument('fileName') ?? 'metocean.txt';
        $path = resource_path($fileName);
        if (!file_exists($path)) {
          Log::debug('The file does not exist.');
          return;
        }

        $dataValueList = [];
        $dataFieldList = [];
        $header = [];
        $handle = fopen($path, 'r');
        while(!feof($handle)){
          $temp = fgets($handle);
          if ($temp != '\n' && trim($temp)) {
            if (self::startsWith($temp, '2014')) {
              $dataValueList[] = explode(',', preg_replace('/\s+/', ',', trim($temp)));
            }
            if (self::startsWith($temp, 'Time')) {
              $header = explode(',', preg_replace('/\s+/', ',', trim($temp)));;
            }
            if (strpos($temp, ' : ')) {
              $fieldList = explode(' : ', $temp);
              $dataFieldList[] = [
                'name' =>trim($fieldList[1]),
                'abbr' => $fieldList[0]
              ];
            }
          }
        }
        // Log::debug($header);
        foreach ($dataFieldList as $item) {
          DataField::updateOrCreate(
            [
            'name' => $item['name'],
            'abbr' => $item['abbr']
            ], 
            [
              'name' => $item['name'],
              'abbr' => $item['abbr']
              ]
          );
        }
        
        // Log::debug($header);
        foreach ($header as $index => $h) {
          if ($index > 0) {
            $result = [];
            preg_match_all('/\[(.*)\]/', $h, $result);
            if (count($result)) {
              
              $field = DataField::firstWhere('abbr', str_replace($result[0], '', $h));
              if ($field) {
                DataField::where('id', $field['id'])->update(['unit' => $result[1][0]]);
                foreach ($dataValueList as $item) {
                  // Log::debug($item);
                  $date = explode('-', $item[0]);
                  $hour = explode(':', $item[1]);
                  DataValue::updateOrCreate(
                    [
                      'value' => $item[$index + 1],
                      'field_id' => $field['id'],
                      'date_time' => Carbon::create($date[0], $date[1], $date[2], $hour[0], $hour[1], $hour[2])
                    ],
                    [
                      'value' => $item[$index + 1],
                      'field_id' => $field['id'],
                      'date_time' => Carbon::create($date[0], $date[1], $date[2], $hour[0], $hour[1], $hour[2])
                    ]
                  );
                }
              }
            }
          }
        }
    }

    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}
