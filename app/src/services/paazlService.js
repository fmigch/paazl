export function paazlService() {
    return fetch('http://127.0.0.1:3000/checkout/paazldata.php').then(value => value.json())
}

export default paazlService