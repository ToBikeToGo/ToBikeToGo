[![Quality Gate Status](https://sonar.tobiketogo.shop/api/project_badges/measure?project=ToBikeToGo_Back&metric=alert_status&token=sqb_093c40c2e01d3e0bd48adc0ad045df69cf77e1ec)](https://sonar.tobiketogo.shop/dashboard?id=ToBikeToGo_Back)
[![Lines of Code](https://sonar.tobiketogo.shop/api/project_badges/measure?project=ToBikeToGo_Back&metric=ncloc&token=sqb_093c40c2e01d3e0bd48adc0ad045df69cf77e1ec)](https://sonar.tobiketogo.shop/dashboard?id=ToBikeToGo_Back)
# Install project : 

```shell
make install
```

### Show all commands

```shell
make # or
make help
```

### Useful commands : 

```shell
> make stop # Stop all docker images
> make start # Start all docker images
> make restart # Restart all docker images
> make migration # Create migration with changes
> make migrate # Apply new migration 
> make composer # Composer install in docker container

> make docker-disable # Disable docker for php container 
> make docker-enable # Enable docker for php container
...
```

If you disable docker for php container, you should create a .env.local file with 

```dotenv
MAILER_DSN=smtp://localhost:1025
DATABASE_URL="postgresql://symfony:password@localhost:5432/postgres?serverVersion=15&charset=utf8"
```
