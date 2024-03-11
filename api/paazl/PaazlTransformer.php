<?php

class PaazlTransformer
{
    public $translate;

    public function __construct($language = 'en_US')
    {
        $this->translate = [];
        if ($language != 'en_US') $this->translate = require 'locale/' . $language . '.php';
    }

    public function getTransformedShippingOptions($data)
    {
        $collectedDeliveryDates = array();
        foreach ($data->shippingOptions as $shippingOption) {
            foreach ($shippingOption->deliveryDates as $deliveryDate) {
                $option = array(
                    'identifier' => $shippingOption->identifier,
                    'label' => $shippingOption->name,
                    'carrier' => $this->translate[$shippingOption->carrier->name] ?? $shippingOption->carrier->name,
                    'description' => $shippingOption->description,
                    'rate' => array(
                        'price' => $shippingOption->rate,
                        'label' => '€ ' . str_replace('.', ',', $shippingOption->rate)
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
                        'label' => ucfirst($this->getTransformedDate($deliveryDate->deliveryDate)),
                        'options' => array($option)
                    );
                }
            }
        }

        sort($collectedDeliveryDates);

        return $collectedDeliveryDates;
    }

    public function getTransformedPickupLocations($data)
    {
        return $data->pickupLocations;
    }

    public function getTransformedDate($inputDate)
    {
        $today = new DateTime('today');
        $date = DateTime::createFromFormat('Y-m-d', $inputDate);

        $diff = $today->diff($date);
        $diffDays = (int) $diff->format('%R%a');

        switch ($diffDays) {
            case 0:
                return $this->translate['today'] ?? 'Today';
            case +1:
                return $this->translate['tomorrow'] ?? 'Tomorrow';
            default:
                return ($this->translate[$date->format('l')] ?? $date->format('l')) . ' ' . $date->format('j') . ' ' . ($this->translate[$date->format('F')] ?? $date->format('F'));
        }
    }
}