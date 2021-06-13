<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
	// ...
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, User::class);
	}

	public function loadUserByUsername($username): ?User
	{
		$entityManager = $this->getEntityManager();

		return $entityManager->createQuery(
			'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :query'
		)
		                     ->setParameter('query', $username)
		                     ->getOneOrNullResult();
	}
}