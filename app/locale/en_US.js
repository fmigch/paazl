export const titles = {
    'title_address': 'Delivery address',
    'title': 'Delivery options',
    'country': 'Country',
    'zipcode': 'Zipcode',
    'delivery_methods': 'Delivery method',
    'delivery_days': 'When do you want to receive it?',
    'delivery_options': 'Delivery service',
    'collect_options': 'Servicepoint'
}

export const allowedCountries = [
    {
        code: 'NL',
        label: 'The Netherlands'
    },
    {
        code: 'BE',
        label: 'Belgium'
    }
]

export const words = {
    'tomorrow': 'Tomorrow'
}

export const deliveryMethods = [
    {
        'label': 'Deliver',
        'type': 'deliver'
    },
    {
        'label': 'Collect',
        'type': 'collect'
    }
]

export const defaultDeliveryOptions = [
    {
        'label': 'Deliver at home address',
        'identifier': 'DHL_FOR_YOU_MONDAY_FRIDAY',
        'carrier': 'DHL',
        'description': 'This is the standard delivery option',
        'rate': {
            'price': 4.05,
            'label': '€ 4,05'
        }
    }
]