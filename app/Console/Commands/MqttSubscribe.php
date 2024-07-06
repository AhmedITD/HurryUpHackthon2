<?php

namespace App\Console\Commands;

use PhpMqtt\Client\Facades\MQTT;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

use App\Models\device;
use App\Models\historicalRead;

use App\Events\ExampleEvent;

class MqttSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to MQTT topics and process messages';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        file_put_contents(public_path("assets/uploades/test.txt"), 'dsd');
        $server   = '91.121.93.94';
        $port     = 1883;
        $clientId = 'mqtt-client';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setKeepAliveInterval(5);

        $mqtt->connect($connectionSettings, true);

        $mqtt->subscribe('aswar', function (string $topic, string $message) {
         echo   $json = $message."\n";


//function fixJsonString($json)
                // Regular expression to match keys and unquoted string values
            $pattern = '/"([^"]+)"\s*:\s*([^",\s{}\[\]]+)/';
                
            // Callback function to add quotes around string values
            $callback = function($matches) {
                // Add quotes around the value if it's not already quoted and not a number
                if (preg_match('/^-?\d+\.?\d*$/', $matches[2]) === 0) {
                    return '"' . $matches[1] . '":"' . $matches[2] . '"';
                } else {
                    return '"' . $matches[1] . '":' . $matches[2];
                }
            };
                
            // Perform the replacement
            $fixedJson = preg_replace_callback($pattern, $callback, $json);
                
            $message = $fixedJson;

            event(new ExampleEvent(['message' => $message]));//////////////////////////////////////////////////
            $data = json_decode($message);
            $ary_data = [
                'tempC' => $data->tempC,
                'humi' => $data->humi,
                'dsm_consentrate'=> $data->dsm_consentrate,
                'dsm_particle' => $data->dsm_particle,
                'air_quality_label' => $data->air_quality_label,
                'sensor_value' => $data->sensor_value,
            ];
            device::where('id','1')->update($ary_data);
            
            $ary_data = [
                'device_id' => '1',
                'tempC' => $data->tempC,
                'humi' => $data->humi,
                'dsm_consentrate'=> $data->dsm_consentrate,
                'dsm_particle' => $data->dsm_particle,
                'air_quality_label' => $data->air_quality_label,
                'sensor_value' => $data->sensor_value,
            ];
            historicalRead::create($ary_data);
            file_put_contents(public_path("assets/uploades/test.txt"), $topic . " " . $data->tempC);
            
            
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo 'Invalid JSON data: ' . json_last_error_msg() . "\n";
                return;
            }


            // $tempC = $data->tempC;
            // $humi = $data->humi;
            // $dsmConsentrate = $data->dsm_consentrate;
            // $dsmParticle = $data->dsm_particle;
            // $airQualityLabel = $data->air_quality_label;
            // $sensorValue = $data->sensor_value;

            // echo "Temperature (C): $tempC\n";
            // echo "Humidity (%): $humi\n";
            // echo "Concentration: $dsmConsentrate\n";
            // echo "Particle: $dsmParticle\n";
            // echo "Air Quality Label: $airQualityLabel\n";
            // echo "Sensor Value: $sensorValue\n";

        }, 0);

        $mqtt->loop(true);

        $mqtt->disconnect();
    }




    function fixJsonString($json)
    {
    // Regular expression to match keys and unquoted string values
    $pattern = '/"([^"]+)"\s*:\s*([^",\s{}\[\]]+)/';
    
    // Callback function to add quotes around string values
    $callback = function($matches) {
        // Add quotes around the value if it's not already quoted and not a number
        if (preg_match('/^-?\d+\.?\d*$/', $matches[2]) === 0) {
            return '"' . $matches[1] . '":"' . $matches[2] . '"';
        } else {
            return '"' . $matches[1] . '":' . $matches[2];
        }
    };
    
    // Perform the replacement
    $fixedJson = preg_replace_callback($pattern, $callback, $json);
    
    return $fixedJson;
    }

}
