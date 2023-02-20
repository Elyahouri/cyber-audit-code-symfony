<?php

namespace App\DataFixtures;

use App\Entity\AnnualSale;
use App\Entity\Company;
use App\Entity\Contribution;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\fr_FR\Address as FakerAddress;
use Faker\Provider\fr_FR\Company as FakerCompany;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\PhoneNumber as FakerPhoneNumber;
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
        $this->loadContributions($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new FakerAddress($faker));
        $faker->addProvider(new FakerPerson($faker));
        $faker->addProvider(new FakerPhoneNumber($faker));
        $faker->addProvider(new FakerCompany($faker));

        $users =
            [
                ["email" => "admin@mail.dev","roles" => ["ROLE_ADMIN"],"password"=>"password"],
                ["email" => "company@mail.dev","roles" => ["ROLE_COMPANY"],"password"=>"password"],
            ];

        for($i = 1; $i<= 10; $i++){
            $users[] = [
                "email"=>$faker->email(),
                "roles" => ["ROLE_COMPANY"],
                "password"=>"password$i"
            ];
        }

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data["email"]);
            $user->setRoles($data["roles"]);
            $user->setPlainPassword($data["password"]);
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
            $manager->persist($user);

            if($user->getRoles() === ["ROLE_COMPANY"]){
                $company = $this->makeCompany($faker);
                $user->setCompany($company);
                $manager->persist($company);
            }

        }
        $manager->flush();
    }

    private function makeCompany(Generator $faker): Company
    {
        $company = new Company();
        $company->setSiret($faker->siret(false));
        $company->setName($faker->company());
        $company->setAddress($faker->address());
        $company->setZipCode($faker->postcode());
        $company->setCity($faker->city());
        $company->setPhone($faker->phoneNumber());


        $company->addAnnualsSale(new AnnualSale('2022', $faker->randomNumber(5, true)));
        $company->addAnnualsSale(new AnnualSale('2023', $faker->randomNumber(5, true)));
        return $company;
    }

    private function loadContributions(ObjectManager $manager){
        $sales = $manager->getRepository(AnnualSale::class)->findAll();

        foreach($sales as $s){

            $contribution = new Contribution();
            $contribution->setYear($s->getYear());
            $contribution->setBase($s->getAmount());
            $contribution->calculate();
            $contribution->setCompany($s->getCompany());

            $manager->persist($contribution);
        }
        $manager->flush();
    }


}
