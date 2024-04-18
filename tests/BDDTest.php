<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BDDTest extends KernelTestCase
{
    public function testCount()
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $entityManager = $container->get(EntityManagerInterface::class);
        $userPasswordHasher = $container->get(UserPasswordHasherInterface::class);

        $user = new User();

        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                '123456'
            )
        );
        $user->setEmail('mailtest@live.fr');
        $user->setIsVerified(false);
        $user->setNom('test');
        $user->setPrenom('test');
        $user->setPseudo('test');

        $entityManager->persist($user);
        $entityManager->flush();

        $this->assertEquals(2, (1 + 1));
        //self::$container->get();
    }
}
