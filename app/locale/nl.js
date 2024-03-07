export const titles = {
    'title': 'Bezorging',
    'delivery_methods': 'Bezorgmethode',
    'delivery_days': 'Wanneer wil je het ontvangen?',
    'delivery_options': 'Bezorgoptie'
}

export const words = {
    'tomorrow': 'morgen'
}

export const deliveryMethods = [
    {
        'label': 'Bezorgen',
        'type': 'deliver'
    },
    {
        'label': 'Afhalen',
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