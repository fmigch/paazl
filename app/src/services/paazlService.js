export function paazlService(apiUrl, data = []) {
    return fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    }).then(value => value.json())
}

export default paazlService