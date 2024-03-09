<?php

$settings = '{
    "consigneeCountryCode": "NL",
    "consigneePostalCode": "7122TM",
    "currency": "EUR",
    "includeExternalDeliveryDates": false,
    "limit": 20,
    "locale": "nl_NL",
    "numberOfProcessingDays": 0,
    "deliveryDateOptions": {
      "startDate": "2024-02-21",
      "numberOfDays": "14"
    },
    "shipmentParameters": {
      "goods": [
        {
          "height": 1,
          "length": 1,
          "price": 10,
          "quantity": 1,
          "volume": 1,
          "weight": 1,
          "width": 1
        }
      ],
      "numberOfGoods": 1,
      "startMatrix": "A",
      "totalPrice": 1,
      "totalVolume": 1,
      "totalWeight": 1
    },
    "sortingModel": {
      "orderBy": "DATE",
      "sortOrder": "ASC"
    }
}';

$settings = json_decode($settings);