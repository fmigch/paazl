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

export const defaultDeliveryDays = [
    {
        'label': 'Morgen',
        'date': '2024-03-04'
    }
]

export const defaultDeliveryOptions = [
    {
        'label': 'Standaard',
        'identifier': 'DHL default',
        'carrier': 'DHL',
        'description': 'Levering standaard',
        'rate': {
            'price': 4.05,
            'label': '4,05'
        }
    }
]