<?php

class PaazlApi
{
    public $url;
    public $apiKey;
    public $apiSecret;
    public $reference;

    public function __construct($reference, $apiKey, $apiSecret, $production = true)
    {
        $this->url = 'https://api-acc.paazl.com/v1/';
        if ($production)
            $this->url = 'https://api.paazl.com/v1/';
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->reference = $reference;
    }

    public function getBearer($endpoint)
    {
        switch ($endpoint) {
            case 'post-shippingoptions':
            case 'post-pickuplocations':
            case 'post-checkout':
                return $this->apiKey;
            default:
                return $this->apiKey . ':' . $this->apiSecret;
        }
    }

    public function getToken()
    {
        $data = '{"reference": "' . $this->reference . '"}';
        $auth = $this->doRequest('checkout/token', 'POST', $data);

        return $auth->token;
    }

    public function getShippingOptions($data, $pickuplocations = false)
    {
        $url = 'shippingoptions';
        if ($pickuplocations)
            $url = 'pickuplocations';

        return $this->doRequest($url, 'POST', $data);
    }

    public function getCheckout($reference)
    {
        return $this->doRequest('checkout?reference=' . $reference);
    }

    public function doRequest($endpoint, $method = 'GET', $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($method != 'GET')
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $this->url . $endpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getBearer('post-' . $endpoint), 'Content-type: application/json'));

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode == 200) {
            return json_decode($result);
        } else {
            $message = '{"error": {"http_code": ' . $httpcode . ',"details": ' . $result . '}}';
            return json_decode($message);
        }
    }
}
