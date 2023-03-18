# How to setup and use
1. Clone code to local env
2. cd to code directory and run
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
3. Run sail up to start container
```
./vendor/bin/sail up -d
```
4. Copy .env.example to .env
5. Run migration and seeder
```
./vendor/bin/sail artisan migrate:refresh --seed
```
6. Import postman collection + environment. I put it it /postman directory.

Accounts
   <p>admin: admin@test.com/111111</p>
   <p>customer 1: customer@test.com/111111</p>
   <p>customer 2: customer2@test.com/111111</p>

Sorry I didn't have enough time to prepare script to install the app in one go. Should you run into any issue installing the app, please reach me at skype: danhkhh

# Technical Notes
	I use Sanctumn for authentication and spatie/laravel-permission for role and permission management.
	I also apply Service layer to keep Controller thin.
	I code with Service interface, then bind AspireService to the interface for now. We can change to another Service easily. The Service Factory will take care which service to bind.
	In the future, as the project grows up, we may consider separating current Service layer into 2 different layers: Repository (data access logic) and Service (business logic)
	
	There are 2 tables to pay attention to: loans, repayments
	As long as user create a 3-term loan, there will be 3 repayments automatically created. 
	In my opinion, this is not time-consuming, there is no need to use queue. Instead, I use observer pattern. I create an observer on loan created event to create the repayments.


# Some features to discuss:
	1. Maybe we should prevent customer from creating a new loan if he is currently in debt.
	2. What if customer makes repayment with amount greater then expected? Should we notify someone?
	3. These 2 tables (loans, repayments) tend to grow bigger and bigger. At some point, we need to consider to move PAID loans to another place. As a result, the tables keep resonable amount of records. On the other hand, we can consider partitioning table (by time/by status).
	4. Shall we have loan report feature? If yes, querying data on large table can be a burden on the performance. We need to think of some solutions (index, partition, caching, ....) depends on the requirements.
