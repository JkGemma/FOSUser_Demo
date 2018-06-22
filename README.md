# FOSUser_Demo

Step 0 (Tips) :

`php bin/console make:entity (maker-bundle)
php bin/console make:migration (orm-pack)
php bin/console doctrine:m:m (orm-pack)`

Step 1 (Symfony 4 Basic Install) :

Install Symfony 4 :
	$ composer create-project symfony/website-skeleton
	
	Optionel :
	$ composer require orm-pack
	$ composer require symfony/maker-bundle --dev
	
	
	
Step 2 (Translating) : 
Traduction : 
	
Pour utiliser les textes par défaut du Bundle, il faut modifier le fichier app/config/services.yaml & y mettre :
(attention à l'indentation du yaml)
	
framework:
	translator: ~
	
	
	
	
Step 3 (Basic Install) :
Suite à celà, nous allons ajouter FOSUserBundle à notre projet.
		
	$ composer require friendsofsymfony/user-bundle "~2.0"
		
		
A ce niveau là, il y aura une erreur :
		
!!	"The child node "db_driver" at path "fos_user" must be configured."
		
Il faut ajouter les lignes ci-dessous dans app/config/services.yaml
		
	fos_user:
		db_driver: orm # other valid values are 'mongodb' and 'couchdb'
		firewall_name: main
		user_class: App\Entity\User 
		from_email:
			address: noreply@example.com
			sender_name: Demo App



Step 4 (Templating Error) :
Relancer ensuite la commande :
	$ composer require friendsofsymfony/user-bundle "~2.1"
		
Nouvelle erreur :
		
The service "fos_user.resetting.controller" has a dependency on a non-exist"ent service "templating".  
Il faudra ajouter les lignes suivante dans app/config/packages/framework.yaml
		
	templating:
		engines: ['twig', 'php']




Step 5 (User Entity): 
Suite à cela, l'installation se fait sans soucis
Pour la suite du guide FOSU, vous aurez besoin d'une Entité User

Dans celle-ci, vous devrez rajouter le use suivant :
		
	use FOS\UserBundle\Model\User as BaseUser;
		
Faire que votre classe User extends de Base User & appeler le construct parent dans le construct de celle-ci
Il faudra ensuite commenter TOUT, sauf la variable "private $id" que vous mettrez en "protected $id".
Ce qui donnera une entité User à peu prés comme celle-ci dessous.
		
namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
		
class User extends BaseUser {
			
	public function __construct(){
		parent::__construct();
		}

	protected $id	
}





Step 6 (Security):
Il faut maintenant modifier le fichier app/config/packages/security.yaml & yy ajouter les lignes suivantes
		
	encoders:
		FOS\UserBundle\Model\UserInterface: bcrypt

	role_hierarchy:
		ROLE_ADMIN:       ROLE_USER
		ROLE_SUPER_ADMIN: ROLE_ADMIN
			
	providers: # déjà présent, ajouter juste les 2 lignes en dessous
		fos_userbundle:
			id: fos_user.user_provider.username

	firewalls: # enlever la ligne dans main & y mettre les suivantes
		main:
			pattern: ^/
			form_login:
				provider: fos_userbundle
				csrf_token_generator: security.csrf.token_manager
			logout:       true
			anonymous:    true

	access_control: # ajouter ceci dans access_control
		- { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
		- { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
		- { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
		- { path: ^/admin/, role: ROLE_ADMIN }




Step 7 (Routing) : 		
Dans le fichier app/config/routes.yaml, vous allez maintenant créer vos routes.
		
	fos_user_security:
		resource: "@FOSUserBundle/Resources/config/routing/security.xml"

	fos_user_profile:
		resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
		prefix: /profile #modifiable

	fos_user_register:
		resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
		prefix: /register #modifiable

	fos_user_resetting:
		resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
		prefix: /resetting #modifiable

	fos_user_change_password:
		resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
		prefix: /profile #modifiable (profile/edit & profile/change_password)
