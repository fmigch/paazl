export function paazlService(apiUrl) {
    return fetch(apiUrl).then(value => value.json())
}

export default paazlService