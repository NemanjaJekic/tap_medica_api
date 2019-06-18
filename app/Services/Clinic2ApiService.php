<?php


namespace App\Services;

use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class Clinic2ApiService extends ApiService
{
    protected $api_url;
    protected $data;
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->api_url = Config::get('app.clinic_api_url');
    }

    public function getClient()
    {
        $this->client = new Client();

        $response = $this->client->post($this->api_url . '/auth', [
            'form_params' => [
                'email' => Config::get('credentials.clinic2.email'),
                'password' => Config::get('credentials.clinic2.password')
            ]
        ]);

        $this->token = $this->getToken($response);

        return $this->client;
    }

    public function getToken($response)
    {
        $body = json_decode($response->getBody(), true);

        return $body['token'];
    }

    public function getData()
    {
        $this->getClient();

        $response = $this->client->get($this->api_url . '/json' , [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token
            ]
        ]);
        $decoded = json_decode($response->getBody()->getContents(), true);

        $i = 1;
        while ($decoded['next_page_url']) {
            $response = $this->client->get($this->api_url . '/json?page=' . $i++ , [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token
                ]
            ]);
            $decoded = json_decode($response->getBody()->getContents(), true);
            $this->data[] = $decoded['data'];
        }

        $collection = collect($this->data);
        $this->data = $collection->collapse();

        return $this;
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
        $date = explode(' ', $element['datetime']);

        return [
            'id' => (string) $element['id'],
            'patientId' => (integer)  $element['patient']['id'],
            'patientName' => (string) $element['patient']['name'],
            'dateOfBirth' => new Carbon($element['patient']['dob']),
            'gender' => (integer) $element['patient']['gender'],
            'doctorId' => (integer) $element['doctor']['id'],
            'doctorName' => (string) $element['doctor']['name'],
            'clinicId' => (integer) $element['clinic']['id'],
            'clinicName' => (string) $element['clinic']['name'],
            'specialtyId' => (integer) $element['specialty']['id'],
            'specialtyName' => (string) $element['specialty']['name'],
            'status' => (bool) ($element['status'] == 'booked' ? 1 : 0),
            'booked_date' => $date[0],
            'booked_time' => DateTime::createFromFormat('H:i:s', $date[1])->format('H:i:s'),
            'created_at' => (string) $element['created_at']
        ];
    }
}