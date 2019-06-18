<?php


namespace App\Services;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class Clinic1ApiService extends ApiService
{
    protected $api_url;
    protected $data;
    protected $client;

    public function __construct()
    {
        $this->api_url = Config::get('app.clinic_api_url');
    }

    public function getClient()
    {
        $client = new Client();

        $this->client = $client->request('GET',
            $this->api_url . '/xml',
            ['auth' => [
                Config::get('credentials.clinic1.username'),
                Config::get('credentials.clinic1.password')
            ]]);

        return $this->client;
    }


    public function getData()
    {
        $response = $this->getClient()
                         ->getBody()
                         ->getContents();

        $this->data = $this->xml($response);
        $this->data = collect($this->data['appointment']);

        return $this;
    }

    public function xml($string) {
        if ($string) {
            $xml = @simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA);
            if(!$xml)
                throw new ParserException('Failed To Parse XML');

            return json_decode(json_encode((array) $xml), 1);
        }

        return array();
    }

    public function mapData()
    {
        foreach ($this->data as $element) {
            $arr[] = $this->map($element);
        }

        $this->data = collect($arr);

        return $this;
    }

    public function map($element)
    {
        $time = Carbon::create($element['start_time']);
        $element['start_time'] = $time->toTimeString();
        $date = Carbon::create($element['start_date']);
        $element['start_date'] = $date->toDateString();

        return [
            'id' => (string) $element['id'],
            'patientId' => (integer)  $element['patient']['id'],
            'patientName' => (string) $element['patient']['name'],
            'dateOfBirth' => new Carbon($element['patient']['date_of_birth']),
            'gender' => (integer) $element['patient']['sex'],
            'doctorId' => (integer) $element['doctor']['id'],
            'doctorName' => (string) $element['doctor']['name'],
            'clinicId' => (integer) $element['clinic']['id'],
            'clinicName' => (string) $element['clinic']['name'],
            'specialtyId' => (integer) $element['specialty']['id'],
            'specialtyName' => (string) $element['specialty']['name'],
            'status' => (bool) !$element['cancelled'],
            'booked_date' =>  $element['start_date'],
            'booked_time' =>  $element['start_time'],
            'created_at' => (string) $element['booked_at']
        ];
    }
}