<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $users =
            [
                ["email" => "admin@mail.dev","roles" => ["ROLE_ADMIN"],"password"=>"password"],
                ["email" => "company@mail.dev","roles" => ["ROLE_COMPANY"],"password"=>"password"],
            ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data["email"]);
            $user->setRoles($data["roles"]);
            $user->setPlainPassword($data["password"]);
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
            $manager->persist($user);
        }
        $manager->flush();
    }


}
