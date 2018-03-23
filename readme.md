<b>Installation:</b>

1] git clone https://github.com/aldryn-gutierrez/inventory-warehouse.git

2] composer install

3] copy .env.example file in the root directory and rename it to .env

4] Inside the .env file that you just created update this fields to your database credentials:

DB_CONNECTION=mysql <br/>
DB_HOST=127.0.0.1 <br/>
DB_PORT=3306 <br/>
DB_DATABASE=homestead <br/>
DB_USERNAME=homestead <br/>
DB_PASSWORD=secret <br/>

5] Run in terminal: php artisan key:generate

This should update your .env APP_KEY, if not copy the output from the terminal

6] Run in terminal: php artisan migrate;

This should create all the database tables

7] Run php artisan db:seed

This should seed some data in your tables

8] Run php artisan serve

This will give you a server to call the API

<b>Documentation</b>

[Create Inventory]

URL: /api/inventory
METHOD: POST

Parameters:
product_id: required|integer
distribution_center_id: required|integer
quantity: required|integer

Response:
400: Validation Error
400: Inventory with same values exists
200: Success

[Adjust Inventory]

URL: /api/inventory/adjust
METHOD: POST

Parameters:
inventory_id: required|integer
inventory_status_id: required|integer

Response:
400: Validation Error
204: Success with no content

[Inventory Report]

URL: /api/inventory/report
METHOD: GET

Response:
200: Success 


