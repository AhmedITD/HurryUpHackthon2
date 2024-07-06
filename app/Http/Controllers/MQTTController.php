<?php

namespace App\Http\Controllers;

use PhpMqtt\Client\Facades\MQTT;
use Illuminate\Http\Request;
use App\Models\device;
use App\Models\historicalRead;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Events\ExampleEvent;
class MQTTController extends Controller
{
    // public function publish(Request $request)
    // {
    //     $mqtt = MQTT::connection('default');

    //     $mqtt->publish('aswar', 'Hello, MQTT!', 0);

    //     $mqtt->disconnect();

    //     return response()->json(['message' => 'Message published successfully']);
    // }

    // public function subscribe()
    // {
    //     // $ip = $request->ip();
    //     // dd($ip);
    //     // $brokerIpAddress = config('mqtt-client.connections.default.host');
    //     $mqtt = MQTT::connection('default');

    //     $mqtt->subscribe('aswar', function (string $topic, string $message) {
    //         // Handle the incoming message
    //         $data = $this->fixJsonString($message);
    //         $data = json_decode($data);
            
    //         // $ary_data = [
    //         //     'tempC' => $data->tempC,
    //         //     'humi' => $data->humi,
    //         //     'dsm_consentrate'=> $data->dsm_consentrate,
    //         //     'dsm_particle' => $data->dsm_particle,
    //         //     'air_quality_label' => $data->air_quality_label,
    //         //     'sensor_value' => $data->sensor_value,
    //         // ];
    //         echo sprintf("Received message on topic [%s]: %s\n", $topic, $data);
    //         file_put_contents(public_path("assets/uploades/test.txt"), $topic . " " . $data->tempC);
        

    //         // device::where('id','1')->update($ary_data);

    //         // $ary_data ['device_id'] = '1';
    //         // historicalRead::create($ary_data);

    //     }, 0);

    //     $mqtt->loop(true);

    //     return response()->json(['message' => 'Subscribed successfully']);
    // }
    public function listen()
    {
        $server   = '91.121.93.94';
        $port     = 1883;
        $clientId = 'mqtt-client';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setKeepAliveInterval(5);

        $mqtt->connect($connectionSettings, true);

        $mqtt->subscribe('aswar', function (string $topic, string $message) {
            $message = $this->fixJsonString($message);
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
            // file_put_contents(public_path("assets/uploades/test.txt"), $topic . " " . $data->tempC);
            
            
            
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
    
    function realTime(device $device) 
    {
        return response()->json($device, 200);
    }

    function historicalRead(device $device) 
    {
        // dd($device);
        // $id = $requsest->id;
        // $data =  historicalRead::where('device_id', '1')->get();
        $deviceId = $device->id;
        $data = HistoricalRead::where('device_id', $deviceId)->get();
        return response()->json($data, 200);
    }

    function websocket(){
        event(new ExampleEvent(['message' => 'Hello, this is a test event!']));
        return 'Event has been sent!';
    }

    function CreateDevice(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
            'ip' => 'required|max:20',
            'location' => 'required|max:50',
            ]);
            $data = [
                'name' => $request->name,
                'ip' => $request->ip,
                'location' => $request->location
            ];
            
            $new = device::create($data);
            if ($new) 
            {
                $data = [
                    'state' => '200',
                    'meseege' => 'created successfully'
                ];
                return response()->json($data, 200);
            }else{
                $data = [
                    'state' => '300',
                    'meseege' => 'Error while uploading!!'
                ];
                return response()->json($data, 200);
            }

    }
    function EditeDevice(Request $request, device $device)
    {
        $data = $request->validate(
            [
                'name' => 'required|max:20',
                'ip' => 'required|max:20',
                'location' => 'required|max:50',
            ]
            );

            $data = [
                'name' => $request->name,
                'ip' => $request->ip,
                'location' => $request->location
            ];
            $update = $device->update($data);
            if ($update) 
            {
                $data = [
                    'state' => '200',
                    'meseege' => 'uptateded successfully'
                ];
                return response()->json($data, 200);
            } else 
            {
                $data = [
                    'state' => '300',
                    'meseege' => 'Error while uploading!!'
                ];
                return response()->json($data, 200);
            }
            return redirect(route(name: 'gcodes'))->with('succes', 'updated');
    }
    function DeleteDevice(device $device)
    {
        $delete = $gcode->delete();
        if ($delete) 
        {
            $data = [
                'state' => '200',
                'meseege' => 'deleted successfully'
            ];
            return response()->json($data, 200);
        } else
        {
            $data = [
                'state' => '300',
                'meseege' => 'Error while deleting!!'
            ];
            return response()->json($data, 200);
        }
    }
    function allDevices()
    {
        $device = device::all();
        return response()->json($device, 200);
    }
}
