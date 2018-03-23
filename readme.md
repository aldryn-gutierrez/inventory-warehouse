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
<br/><br/>
[Create Inventory]

URL: /api/inventory<br/>
METHOD: POST<br/>

Parameters:<br/>
product_id: required|integer<br/>
distribution_center_id: required|integer<br/>
quantity: required|integer<br/>

Response:<br/>
400: Validation Error<br/>
400: Inventory with same values exists<br/>
200: Success<br/>

<br/>
[Adjust Inventory]

URL: /api/inventory/adjust<br/>
METHOD: POST<br/>

Parameters:<br/>
inventory_id: required|integer<br/>
inventory_status_id: required|integer<br/>

Response:<br/>
400: Validation Error<br/>
204: Success with no content<br/>
<br/>
[Inventory Report]

URL: /api/inventory/report<br/>
METHOD: GET<br/>

Response:<br/>
200: Success <br/>


<b>Database Diagram</b>
<img src="https://preview.ibb.co/gN2ZZc/Screen_Shot_2018_03_23_at_2_14_45_PM.png" />
