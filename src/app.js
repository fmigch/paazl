import Alpine from '.././node_modules/alpinejs/dist/module.esm.js'

const deliveryMethods = [
    {
        'label': 'Deliver',
        'type': 'deliver'
    },
    {
        'label': 'Collect',
        'type': 'collect'
    }
]

const deliveryDays = [
    {
        'label': 'Tomorrow',
        'date': '2024-3-4'
    }
]

const deliveryOptions = [
    {
        'label': 'Standard',
        'identifier': 'DHL'
    }
]

Alpine.data('app', () => ({
    deliveryMethods: deliveryMethods,
    selectedDeliveryMethod: deliveryMethods[0].type,
    deliveryDays: deliveryDays,
    deliveryOptions: deliveryOptions
}))

Alpine.start()