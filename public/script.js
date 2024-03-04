import Alpine from '.././node_modules/alpinejs/dist/module.esm.js'
import { deliveryMethods, defaultDeliveryDays, defaultDeliveryOptions } from '.././config/default.js'
import paazlService from '.././src/services/paazlService.js'

Alpine.data('app', () => ({
    deliveryMethods: deliveryMethods,
    deliveryDays: defaultDeliveryDays,
    deliveryOptions: defaultDeliveryOptions,

    selectedDeliveryMethod: deliveryMethods[0].type,
    selectedDeliveryDay: defaultDeliveryDays[0].date,
    selectedDeliveryOption: defaultDeliveryOptions[0].identifier,

    async getData() {
        try {
            const response = await paazlService()
            this.deliveryDays = response
            this.deliveryOptions = response[0].options
            this.selectedDeliveryOption = response[0].options[0].identifier
        } catch {
            console.log('Could not connect to Paazl')
        }
    }
}))

Alpine.start()