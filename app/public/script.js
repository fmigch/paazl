import Alpine from '.././node_modules/alpinejs/dist/module.esm.js'
import { apiUrl, allowedCountries } from '.././config/settings.js'
import paazlService from '.././src/services/paazlService.js'
import { titles, countries, words, deliveryMethods, defaultDeliveryOptions } from '.././locale/nl_NL.js' // en_US.js

let deliveryDays = []
let collectOptions = []
let deliveryOptions = []

function getTranslation(e, i) {
	allowedCountries[i].label = countries[e.code]
}

allowedCountries.forEach(getTranslation)

Alpine.data('app', () => ({
	reference: 'TEST190220243',

	isLoadingDeliveryOptions: false,
	isLoadingCollectOptions: false,

	titles: titles,

	selectedZipcode: '7271CB',
	selectedCountry: allowedCountries[0].code,
	countries: allowedCountries,
	addressValidated: false,

	deliveryMethods: deliveryMethods,
	deliveryDays: deliveryDays,
	deliveryOptions: deliveryOptions,

	selectedDeliveryMethod: deliveryMethods[0].type,
	deliveryMethodsDisabled: true,
	selectedDeliveryDay: '',
	selectedDeliveryOption: '',

	collectOptions: collectOptions,
	selectedCollectOption: '',

	showDeliveryMethods: true,
	showDeliveryDays: false,
	showDeliveryOptions: false,
	showCollectOptions: false,

	// default
	shipmentParameters: {
		goods: [
			{
				height: 4,
				length: 4,
				price: 10,
				quantity: 1,
				volume: 1,
				weight: 1,
				width: 1
			}
		],
		numberOfGoods: 2,
		totalPrice: 1,
		totalVolume: 1,
		totalWeight: 1
	},

	async getShippingOptions() {
		try {
			this.deliveryMethodsDisabled = true
			this.isLoadingDeliveryOptions = true

			const payload = {
				consigneeCountryCode: this.selectedCountry,
				consigneePostalCode: this.selectedZipcode,
				shipmentParameters: this.shipmentParameters,
				reference: this.reference
			}

			const response = await paazlService(apiUrl + '/shippingoptions.php', payload)

			this.deliveryDays = response
			this.selectedDeliveryDay = this.deliveryDays[0].date
			this.deliveryOptions = response[0].options
			this.selectedDeliveryOption = response[0].options[0].identifier

			this.isLoadingDeliveryOptions = false
			this.deliveryMethodsDisabled = false
			this.showDeliverSections()
		} catch {
			this.isError()
		}
	},

	async getPickupLocations() {
		try {
			this.deliveryMethodsDisabled = true
			this.isLoadingCollectOptions = true

			const payload = {
				consigneeCountryCode: this.selectedCountry,
				consigneePostalCode: this.selectedZipcode,
				shipmentParameters: this.shipmentParameters,
				reference: this.reference
			}

			const response = await paazlService(apiUrl + '/pickuplocations.php', payload)

			this.collectOptions = response
			this.selectedCollectOption = response[0].code

			this.isLoadingCollectOptions = false
			this.deliveryMethodsDisabled = false
			this.showCollectSections()
		} catch {
			this.isError()
		}
	},

	updateDeliveryOptions(event) {
		const i = this.deliveryDays.findIndex(item => item.date === event.target.value)
		this.deliveryOptions = this.deliveryDays[i].options
		this.selectedDeliveryOption = this.deliveryDays[i].options[0].identifier
	},

	validateZipcode() {
		if (this.selectedCountry == 'BE') {
			const validated = /^[1-9][0-9]{3}$/i

			if (validated.test(this.selectedZipcode)) {
				return true
			} else {
				return false
			}
		} else {
			// default NL
			const validated = /^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i

			if (validated.test(this.selectedZipcode)) {
				return true
			} else {
				return false
			}
		}
	},

	changeAddress() {
		if (this.selectedDeliveryMethod == 'collect') {
			this.deliveryOptions = []
		} else {
			this.collectOptions = []
		}

		this.validateAddress()
	},

	validateAddress() {
		this.addressValidated = false

		if (this.validateZipcode()) {
			this.addressValidated = true

			if (this.selectedDeliveryMethod == 'collect') {
				this.hideCollectSections()
				this.getPickupLocations()
			} else {
				this.hideDeliverSections()
				this.getShippingOptions()
			}
		} else {
			this.addressValidated = false
		}
	},

	changeDeliveryMethod() {
		if (this.selectedDeliveryMethod == 'collect') {
			this.hideDeliverSections()

			if (this.collectOptions.length == 0) {
				this.validateAddress()
			} else {
				this.showCollectSections()
			}
		} else {
			this.hideCollectSections()

			if (this.deliveryOptions.length == 0) {
				this.validateAddress()
			} else {
				this.showDeliverSections()
			}
		}
	},

	showDeliverSections() {
		this.showDeliveryDays = true
		this.showDeliveryOptions = true
	},

	hideDeliverSections() {
		this.showDeliveryDays = false
		this.showDeliveryOptions = false
	},

	showCollectSections() {
		this.showCollectOptions = true
	},

	hideCollectSections() {
		this.showCollectOptions = false
	},

	isError() {
		console.log('Could not connect to Paazl')

		let tomorrow = new Date()
		tomorrow.setUTCDate(tomorrow.getUTCDate() + 1)

		this.deliveryDays = [
			{
				'label': words.tomorrow[0].toUpperCase() + words.tomorrow.slice(1).toLowerCase(),
				'date': tomorrow.toISOString().substring(0, 10)
			}
		]

		this.showDeliveryMethods = false
		this.deliveryMethodsDisabled = true
		this.deliveryOptions = defaultDeliveryOptions
		this.selectedDeliveryDay = this.deliveryDays[0].date
		this.selectedDeliveryOption = defaultDeliveryOptions[0].identifier

		this.isLoadingDeliveryOptions = false
		this.showDeliverSections()
	}

}))

Alpine.start()