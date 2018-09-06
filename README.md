# FOSUserBundle 2.1 - Symfony 4 Guide [DEMO]

## PRE-REQUISITES

	PHP 7.2 & MySQL
	Not for production uses.
	
If you clone this repository, make sure to do the command line below 

	$ composer update

## Step 0 - Tips
Useful Commands
	
	$ php bin/console make:entity
	$ php bin/console make:migration
	$ php bin/console doctrine:m:m	


## Step 1 - Basics
Symfony 4 Installation
		
	$ composer create-project symfony/website-skeleton project_name	
		
## Step 2 - Translating
If you wish to use default texts provided in this bundle, you have to make sure you have translator enabled in your config.

```yaml
# app/config/services.yaml
framework:
    translator: ~
```	


## Step 3 - FOSUserBundle Installation
In this step, you may want to add FOSuserBundle in your project folder.
		
	$ composer require friendsofsymfony/user-bundle "~2.1"

You will get an error at this point, which is 
		
	!! "The child node "db_driver" at path "fos_user" must be configured." !!
		
To fix it, you'll need to add 

```yaml
# app/config/services.yaml	
fos_user:
    db_driver: orm # other valid values are 'mongodb' and 'couchdb'
    firewall_name: main
    user_class: App\Entity\User 
    from_email:
        address: noreply@example.com
        sender_name: Demo App
```

Try again to launch the installation 

	$ composer require friendsofsymfony/user-bundle "~2.1"

## Step 4 - Templating Error
After Step 3, there is another error about templating
		
	The service "fos_user.resetting.controller" has a dependency on a non-exist"ent service "templating".  
	
To fix it, you'll need to add
	
```yaml
# app/config/packages/framework.yaml
framework:
    templating:
        engines: ['twig', 'php']
```

Now, the install of FOSUserBundle will work, you can go to step 5 to go further.
	
	$ composer require friendsofsymfony/user-bundle "~2.1"


## Step 5 - User Entity 
If you need more information about the User Entity, make sure to read Step 3 of FOSUserBundle Symfony Doc below (see Credits & Sources).

If you're persisting your users via the Doctrine ORM, then your `User` class should live in the `Entity` namespace of your bundle and look like this to start:

```php
// app/src/Entity/User.php
namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity(repositoryClass="App\Repository\UserRepository")
*/
class User extends BaseUser
{
    public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* @ORM\Id()
	* @ORM\GeneratedValue()
	* @ORM\Column(type="integer")
	*/
	
	protected $id;
}

```

	
## Step 6 - Security 
In order for Symfony's security component to use the FOSUserBundle, you must tell it to do so in the  `security.yaml` file. The  `security.yaml` file is where the basic security configuration for your application is contained.

Below is a minimal example of the configuration necessary to use the FOSUserBundle in your application:

```yaml
# app/config/packages/security.yaml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username

    firewalls:
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_token_generator: security.csrf.token_manager
            logout:       true
            anonymous:    true

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
```


## Step 7 - Routing 
Now that you have activated and configured the bundle, all that is left to do is import the FOSUserBundle routing files.
By importing the routing files you will have ready made pages for things such as logging in, creating users, etc.

```yaml
# app/config/routes.yaml
fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /profile

fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /register

fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /resetting

fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile
```

## Credits & Sources

Me. (Flayac 'JkGemma' Quentin, Student @Samsung Campus)
https://www.linkedin.com/in/flayacquentin/

Symfony Doc. https://symfony.com/doc/current/bundles/FOSUserBundle/index.html

FriendsOfSymfony Bundle. https://github.com/FriendsOfSymfony/FOSUserBundle
