<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
    	$user = new User();
		$user->setName('test');
		$user->setUsername('test');
//	    $user->setPassword($this->passwordEncoder->encodePassword( $user, 'test'));
	    $user->setPassword( base64_encode('test') );
		$user->setApiToken('test');
		$user->setCreatedAt( new \DateTime() );
		$user->setUpdatedAt( new \DateTime() );
		$manager->persist( $user );

        $manager->flush();
    }
}
