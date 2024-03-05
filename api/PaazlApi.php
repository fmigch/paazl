<?php

class PaazlApi
{
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

        return $this->getTransformedShippingOptions($this->doRequest($url, 'POST', $data));
    }

    public function getTransformedShippingOptions($shippingOptions)
    {
        $collectedDeliveryDates = array();
        foreach ($shippingOptions->shippingOptions as $shippingOption) {
            foreach ($shippingOption->deliveryDates as $deliveryDate) {
                $option = array(
                    'identifier' => $shippingOption->identifier,
                    'label' => $shippingOption->name,
                    'carrier' => $this->getCarrierName($shippingOption->carrier->name),
                    'description' => $shippingOption->description,
                    'rate' => array(
                        'price' => $shippingOption->rate,
                        'label' => str_replace('.', ',', $shippingOption->rate)
                    )
                );

                if (isset($collectedDeliveryDates[$deliveryDate->deliveryDate])) {
                    if (!in_array($shippingOption->identifier, $collectedDeliveryDates[$deliveryDate->deliveryDate]['options'])) {
                        $alreadyExists = false;
                        foreach ($collectedDeliveryDates[$deliveryDate->deliveryDate]['options'] as $deliveryDateOption) {
                            if ($deliveryDateOption['identifier'] == $option['identifier'])
                                $alreadyExists = true;
                        }

                        if (!$alreadyExists)
                            $collectedDeliveryDates[$deliveryDate->deliveryDate]['options'][] = $option;
                    }
                } else {
                    $collectedDeliveryDates[$deliveryDate->deliveryDate] = array(
                        'date' => $deliveryDate->deliveryDate,
                        'label' => $this->getTransformedDate($deliveryDate->deliveryDate),
                        'options' => array($option)
                    );
                }
            }
        }
        sort($collectedDeliveryDates);

        return $collectedDeliveryDates;
    }

    public function getTransformedDate($inputDate)
    {
        $today = new DateTime('today');
        $date = DateTime::createFromFormat('Y-m-d', $inputDate);

        $diff = $today->diff($date);
        $diffDays = (int) $diff->format('%R%a');

        switch ($diffDays) {
            case 0:
                return 'Vandaag';
            case +1:
                return 'Morgen';
            default:
                return ucfirst($this->getTranslation($date->format('l'))) . ' ' . $date->format('j') . ' ' . $this->getTranslation($date->format('F'));
        }
    }

    public function getTranslation($string)
    {
        switch ($string) {
            case 'January':
                return 'januari';
            case 'February':
                return 'februari';
            case 'March':
                return 'maart';
            case 'May':
                return 'mei';
            case 'June':
                return 'juni';
            case 'July':
                return 'juli';
            case 'August':
                return 'augustus';
            case 'october':
                return 'oktober';
            case 'Monday':
                return 'maandag';
            case 'Tuesday':
                return 'dinsdag';
            case 'Wednesday':
                return 'woensdag';
            case 'Thursday':
                return 'donderdag';
            case 'Friday':
                return 'vrijdag';
            case 'Saturday':
                return 'zaterdag';
            case 'Sunday':
                return 'zondag';
            case 'Tomorrow':
                return 'morgen';
            default:
                return $string;
        }
    }

    public function getCarrierName($identifier)
    {
        switch ($identifier) {
            case 'DHL_EXPRESS':
                return 'DHL';
            default:
                return ucfirst(strtolower($identifier));
        }
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
