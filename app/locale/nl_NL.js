export const titles = {
    'title_address': 'Bezorgadres',
    'title': 'Bezorging',
    'country': 'Land',
    'zipcode': 'Postcode',
    'delivery_methods': 'Bezorgmethode',
    'delivery_days': 'Wanneer wil je het ontvangen?',
    'delivery_options': 'Bezorgoptie',
    'collect_options': 'Ophaalpunt'
}

export const allowedCountries = [
    {
        code: 'NL',
        label: 'Nederland'
    },
    {
        code: 'BE',
        label: 'België'
    }
]

export const words = {
    'tomorrow': 'morgen'
}

export const deliveryMethods = [
    {
        'label': 'Bezorgen',
        'type': 'deliver'
    },
    {
        'label': 'Ophalen',
        'type': 'collect'
    }
]

export const defaultDeliveryOptions = [
    {
        'label': 'Levering op huisadres',
        'identifier': 'DHL_FOR_YOU_MONDAY_FRIDAY',
        'carrier': 'DHL',
        'description': 'Dit is de standaard bezorgoptie',
        'rate': {
            'price': 4.05,
            'label': '€ 4,05'
        }
    }
]