This is a sample implementation using PHP, Alpine JS and Tailwind CSS of the Paazl REST API as an alternative to using the Paazl widget.

Extra features
- Fallback method when API fails
- Transform carrier names
- Transform deliveryoption dates

Installation
1. Clone repo.
2. npm install.
3. Add .env file in api folder with PAAZL_APIKEY and PAAZL_APISECRET values.
4. Language for api can be set via config/settings.php. Dutch and English are supported.
5. Run the api folder on a PHP server.
6. Update api url in app config/settings.js.
7. Language for app can be set via public/script.js.
8. Run the app via public/index.html.

Preview<br><br>
![preview](https://github.com/fmigch/paazl/assets/128748261/458f8307-ee8a-4bcf-8708-69a774679ff4)