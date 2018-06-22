<?php
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
