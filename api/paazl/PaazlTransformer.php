<?php

class PaazlTransformer
{
    public $transformCarrier;
    public $transformDate;
    public $translate;
    public $language;

    public function __construct($language = 'en_US')
    {
        $this->transformCarrier = require 'config/transformCarrier.php';
        $this->transformDate = require 'config/transformDate.php';

        $this->language = $language;
        $this->translate = [];
        if ($this->language != 'en_US')
            $this->translate = require 'locale/' . $this->language . '.php';
    }

    public function getTransformedShippingOptions($data)
    {
        $collectedDeliveryDates = array();
        $i = 0;
        foreach ($data->shippingOptions as $shippingOption) {
            foreach ($shippingOption->deliveryDates as $deliveryDate) {
                $option = array(
                    'identifier' => $shippingOption->identifier,
                    'label' => $shippingOption->name,
                    'carrier' => $this->transformCarrier[$shippingOption->carrier->name] ?? $shippingOption->carrier->name,
                    'description' => $shippingOption->description ?? null,
                    'rate' => array(
                        'price' => $shippingOption->rate,
                        'label' => '€ ' . str_replace('.', ',', $shippingOption->rate)
                    )
                );

                $days = $this->transformDate[$shippingOption->identifier];
                if($days) {
                    $deliveryDate->deliveryDate = date('Y-m-d', strtotime($days, strtotime($deliveryDate->deliveryDate)));
                }

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
                        'label' => ucfirst($this->getTransformedDate($deliveryDate->deliveryDate)),
                        'options' => array($option)
                    );
                }

                $i++;
            }
        }

        sort($collectedDeliveryDates);

        return $collectedDeliveryDates;
    }

    public function getTransformedPickupLocations($data)
    {
        $collectedPickupLocations = array();

        for ($i = 0; $i < 5; $i++) {
            $dates = array();

            foreach ($data->pickupLocations[$i]->shippingOptions as $shippingOption) {
                foreach ($shippingOption->deliveryDates as $deliveryDate) {
                    $dates[$deliveryDate->deliveryDate] = array(
                        'date' => $deliveryDate->deliveryDate,
                        'identifier' => $shippingOption->identifier,
                        'carrier' => $this->transformCarrier[$shippingOption->carrier->name] ?? $shippingOption->carrier->name,
                        'rate' => array(
                            'price' => $shippingOption->rate,
                            'label' => '€ ' . str_replace('.', ',', $shippingOption->rate)
                        )
                    );
                }
            }

            sort($dates);

            $collectedPickupLocations[] = array(
                'code' => $data->pickupLocations[$i]->code,
                'label' => $data->pickupLocations[$i]->name,
                'description' => ucfirst(str_replace('%d', $this->getTransformedDate($dates[0]['date']), ($this->translate['can_be_picked_up_from'] ?? 'can be picked up from %d'))),
                'identifier' => $dates[0]['identifier'],
                'carrier' => $this->translate[$dates[0]['carrier']] ?? $dates[0]['carrier'],
                'rate' => $dates[0]['rate']
            );
        }

        return $collectedPickupLocations;
    }

    public function getTransformedDate($inputDate)
    {
        $today = new DateTime('today');
        $date = DateTime::createFromFormat('Y-m-d', $inputDate);

        $diff = $today->diff($date);
        $diffDays = (int) $diff->format('%R%a');

        switch ($diffDays) {
            case 0:
                return $this->translate['today'] ?? 'today';
            case +1:
                return $this->translate['tomorrow'] ?? 'tomorrow';
            default:
                if($this->language == 'en_US') {
                    return ($this->translate[$date->format('l')] ?? $date->format('l')) . ', ' . ($this->translate[$date->format('F')] ?? $date->format('F') . ' ' . $date->format('j') );
                } else {
                    return ($this->translate[$date->format('l')] ?? $date->format('l')) . ' ' . $date->format('j') . ' ' . ($this->translate[$date->format('F')] ?? $date->format('F'));
                }
        }
    }
}
