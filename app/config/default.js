export const apiUrl = 'http://localhost:3000/paazl/api/shippingoptions.php'

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

let tomorrow = new Date()
tomorrow.setUTCDate(tomorrow.getUTCDate() + 1)

export const defaultDeliveryDays = [
    {
        'label': 'Tomorrow',
        'date': tomorrow.toISOString().substring(0, 10)
    }
]

export const defaultDeliveryOptions = [
    {
        'label': 'Delivery to home address',
        'identifier': 'DHL_FOR_YOU_MONDAY_FRIDAY',
        'carrier': 'DHL',
        'description': 'This is standard delivery',
        'rate': {
            'price': 4.05,
            'label': '4,05'
        }
    }
]