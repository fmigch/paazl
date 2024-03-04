export function paazlService() {
    try {
        return fetch('http://127.0.0.1:3000/checkout/paazldata.php').then(value => value.json());
    } catch (error) {
        throw new Error('could not connect to Paazl');
    }
}

export default paazlService