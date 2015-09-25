# OAuth-Server-Demo
This is demo setup of https://github.com/FriendsOfSymfony/FOSOAuthServerBundle for Avazanga needs.
It contains OAuth2 server along with user provider (DB). For better user management maybe https://github.com/FriendsOfSymfony/FOSUserBundle should be used.
But you need to pay close attention to encoder `AppBundle\Security\AvaEncoder` that makes it possible for OAuth Server to validate user password.

### Installation
1. Clone the repository into local folder on your machine's web server and run symfony project
```sh
$ cd /var/www/html
$ git clone https://github.com/Avazanga1/OAuth-Server-Demo.git oauth_server
$ cd oauth_server
$ composer install
```
2. Copy `parameters.yml.dist` to `parameters.yml` and configure database access
3. Run `php app/console doctrine:database:create` to create database
4. Clear cache and rebuild assets:
```
$ php app/console assetic:dump
$ php app/console cache:clear
```
5. Run `php app/console doctrine:schema:update --force` to create database schema
6. Create virtualhost on you local web serwer:

Sample for Apache 2.4:
```conf
<VirtualHost *:80 >
	ServerAdmin admin@localhost.com
	DocumentRoot "var/www/html/oauth_server/web"
	ServerName ava.oauth_server.local
	
	Header set Access-Control-Allow-Origin *
	Header set Access-Control-Allow-Methods "GET,POST,PUT,OPTIONS"
	Header set Access-Control-Allow-Headers "authorization, Authorization"
	
	ErrorLog "logs/ava.oauth_server.local-error.log"
	CustomLog "logs/ava.oauth_server.local-access.log" common
	<directory 	"var/www/html/oauth_server/web">
		AllowOverride All
	</directory>
</VirtualHost>
```

6. Add following line to hosts file:
```
127.0.0.1 ava.oauth_server.local
```
7. Restart you webserver and navigate to `http://ava.oauth_server.local/`

Your should see message like `Homepage. Hi undefined`. It means you are not logged in

8. Create new user in the system. *At this point I don't remember how it should be done...*

### Configuration

If you have your server & client instances up and running, and don't know what to do next you're in good place.
You need to create client entity in server and use obtained credentials to configure your client.

**Assumptions**
 - Client's application URL is: `http://ava.client_symfony.local/`

**Configuration**
1. Client entity creation 

Open terminal for server and execute command: 
```
php app/console ava:security:oauth:client:create "Symfony Client" --redirect-uri="http://ava.client_symfony.local/" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh_token" --grant-type="token" --grant-type="client_credentials"
```

You'll get response like: 
```
Added a new client with name Symfony Client and public id 2_1nertts0x0bo4ksg080ksggwggogo8o4cwk8kkow0skoswww4g, secret 3iulvkug0m80s4cg80w4w0c8ccwccc04ggsogsg8co8coskok0.
```

Note public id and secret because you'll need them in the next step.

2. Client application configuration:

Open client application project and edit `app/config/parameters.yml` file by updating parameters: 
```
oauth_client: 2_1nertts0x0bo4ksg080ksggwggogo8o4cwk8kkow0skoswww4g
oauth_secret: 3iulvkug0m80s4cg80w4w0c8ccwccc04ggsogsg8co8coskok0
website_back_base_url: http://ava.oauth.local
```

Now you can refresh cache.

### Using the system

Now when both server & client are installed and configured we can test them.

1. Open your browser and navigate to: `http://ava.client_symfony.local/app/example`
2. You should be redirected to `http://ava.client_symfony.local/login/` where you will see `avazanga` link.
3. By hitting it the browser will open server's (notice URL in the browser) login page
4. Provide user login and password
5. Optionally you'll see grant access screen. You need to agree.
6. You'll be redirected back to `http://ava.client_symfony.local/app/example` and browser will show some dumps of current user.

***Enjoy!***