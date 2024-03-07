export const titles = {
    'title': 'Delivery',
    'delivery_methods': 'Delivery method',
    'delivery_days': 'When do you want to receive it?',
    'delivery_options': 'Delivery option'
}

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