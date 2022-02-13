<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;



class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="fetch_users", methods={"GET"})
     */
    public function allUsers( ManagerRegistry $doctrine): JsonResponse
    {
    try {
       
       
       $data = $doctrine
       ->getRepository(User::class)
       ->findAllOrderedByName();
       $response = new JsonResponse(['status' => True, 'data'=> $data]);
        
        return $response;
    } catch(\Exception $e){
        return new JsonResponse(['status' => false, 'errors'=> 'an error occured']);
      }
    }
    /**
     * @Route("/api/user/{id}", name="show_user", methods={"GET"})
     */
    public function showUser(string $id, ManagerRegistry $doctrine): JsonResponse
    {
    try {
        $user =  $doctrine->getRepository(User::class)->findOneBy(['uuid' => $id]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        return new JsonResponse(['status' => True, 'data' =>  'user : '.$user->getName(). 'founded!']);
    } catch(\Exception $e){
        return new JsonResponse(['status' => false, 'errors'=> 'an error occured']);
      }
    }
    /**
     * @Route("/api/user", name="create_user", methods={"POST"})
     * @param Request $request
     * @return /Symfony\Component\HttpFoundation\JsonResponse;
     */
    
    public function createUser(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        try {
        $data = json_decode($request->getContent(), true);
        $validator = Validation::createValidator();
       
        
        $groups = new Assert\GroupSequence(['Default', 'custom']);

        $constraint = new Assert\Collection([
            // the keys correspond to the keys in the input array
            'name' => new Assert\Length(['min' => 4]),
            'email' => new Assert\Email(),
            'username' => new Assert\Length(['min' => 10]),
            'password' => new Assert\Length(['min' => 8]),
          
        ]);
        
        $violations = $validator->validate($data, $constraint, $groups);
        if(count($violations) != 0){
            foreach($violations as $violation)
            {
                $errors[] = [$violation->getPropertyPath() => $violation->getMessage()];
            }
            return new JsonResponse(['status' => false, 'errors'=> $errors]);
        }
        $entityManager = $doctrine->getManager();
       
        $user = new User();
       
        // Configure different password hashers via the factory
        $factory = new PasswordHasherFactory([
        'common' => ['algorithm' => 'bcrypt'],
        'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        // Retrieve the right password hasher by its name
        $passwordHasher = $factory->getPasswordHasher('common');

        // Hash a plain password
        $hashedPassword = $passwordHasher->hash($data['password']); // returns a bcrypt hash
        $user->setName($data['name']);
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($hashedPassword);
         // tell Doctrine you want to (eventually) save the Product (no queries yet)
         $entityManager->persist($user);

         // actually executes the queries (i.e. the INSERT query)
         $entityManager->flush();
        $response = JsonResponse::fromJsonString('{ "status": true, "data": "User successfully created" }');
        return $response;
    } catch(\Exception $e){
        return new JsonResponse(['status' => false, 'errors'=> 'an error occured']);
      }
    }
       /**
     * @Route("/api/user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(string $id, ManagerRegistry $doctrine): JsonResponse
    {
        try {
        // find user by uuid 
        $user = $doctrine->getRepository(User::class)->findOneBy(['uuid' => $id]);
        if (!$user) {
            return new JsonResponse(['status' => False, 'data' =>  'user did not found!']);
        }
        // remove user from database TODO use soft delete
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(['status' => True, 'data' =>  'user : '.$user->getName(). ' deleted!']);
    } catch(\Exception $e){
        return new JsonResponse(['status' => false, 'errors'=> 'an error occured']);
      }
    }
    /**
     * @Route("/api/user/{id}", name="update_user", methods={"PUT"})
     */
    public function updateUser(ManagerRegistry $doctrine, Request $request, string $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $validator = Validation::createValidator();
           
            
            $groups = new Assert\GroupSequence(['Default', 'custom']);
    
            $constraint = new Assert\Collection([
                // the keys correspond to the keys in the input array
                'name' => new Assert\Length(['min' => 4]),
            ]);
            
            $violations = $validator->validate($data, $constraint, $groups);
            if(count($violations) != 0){
                $errors = json_encode($violations);
                return new JsonResponse(['status' => false, 'errors'=> $errors]);
            }
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['uuid' => $id]);

        if (!$user) {
            return new JsonResponse(['status' => false, 'data' =>  'user : '.$user->getName(). ' did not found!']);
        }

        $user->setName($data['name']);
        $entityManager->persist($user);

         // actually executes the queries (i.e. the INSERT query)
         $entityManager->flush();
        return new JsonResponse(['status' => true, 'data' =>  'user : '.$user->getName(). ' updated!']);
    } catch(\Exception $e){
        return new JsonResponse(['status' => false, 'errors'=> 'an error occured']);
      }
    }
}