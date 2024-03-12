import Alpine from '.././node_modules/alpinejs/dist/module.esm.js'
import { pickupLocationsUrl, shippingOptionsUrl } from '.././config/settings.js'
import paazlService from '.././src/services/paazlService.js'
import { titles, words, deliveryMethods, defaultDeliveryOptions } from '.././locale/nl_NL.js'

let deliveryDays, deliveryOptions, collectOptions = []

Alpine.data('app', () => ({
	isLoading: true,
	isLoadingCollectOptions: false,
	hasError: false,
	
	titles: titles,

	deliveryMethods: deliveryMethods,
	deliveryDays: deliveryDays,
	deliveryOptions: deliveryOptions,
	
	selectedDeliveryMethod: deliveryMethods[0].type,
	selectedDeliveryDay: '',
	selectedDeliveryOption: '',

	collectOptions: collectOptions,
	selectedCollectOption: '',

	async getShippingOptions() {
		try {
			const response = await paazlService(shippingOptionsUrl)
			this.deliveryDays = response
			this.deliveryOptions = response[0].options
			this.selectedDeliveryOption = response[0].options[0].identifier
			this.isLoading = false
		} catch {
			this.isLoading = false
			this.hasError = true

			console.log('Could not connect to Paazl')

			let tomorrow = new Date()
			tomorrow.setUTCDate(tomorrow.getUTCDate() + 1)

			this.deliveryDays = [
				{
					'label': words.tomorrow[0].toUpperCase() + words.tomorrow.slice(1).toLowerCase(),
					'date': tomorrow.toISOString().substring(0, 10)
				}
			]

			this.deliveryOptions = defaultDeliveryOptions,
				this.selectedDeliveryDay = this.deliveryDays[0].date,
				this.selectedDeliveryOption = defaultDeliveryOptions[0].identifier
		}
	},

	async getPickupLocations() {
		if(this.collectOptions.length == 0) {
			this.isLoadingCollectOptions = true
			const response = await paazlService(pickupLocationsUrl)
			this.collectOptions = response
			console.log(response)
			this.selectedCollectOption = response[0].code
			this.isLoadingCollectOptions = false
		}
	},

	updateDeliveryOptions(event) {
		const i = this.deliveryDays.findIndex(item => item.date === event.target.value)
		this.deliveryOptions = this.deliveryDays[i].options
		this.selectedDeliveryOption = this.deliveryDays[i].options[0].identifier
	}
}))

Alpine.start()