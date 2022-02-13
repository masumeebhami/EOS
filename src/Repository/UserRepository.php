<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;



class UserRepository extends EntityRepository
{
   
// find all users and order by name 
// just show uuid , name and email
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p.uuid, p.name, p.email FROM App:User p ORDER BY p.name ASC'
            )
            ->getResult();
    }
   
    
}