<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Customer;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    protected $faker;

    public const CUSTOMER_EXAMPLE = ["EXAMPLE1","EXAMPLE2","EXAMPLE3","EXAMPLE4","EXAMPLE5"];

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("fr_FR");

        for ($i=0; $i<5; $i++){
            $customer = new Customer();
            $customer->setName($this->faker->name());
            $customer->setEmail($this->faker->unique()->email());
            $customer->setPassword($this->faker->password());

            $this->addReference(self::CUSTOMER_EXAMPLE[$i], $customer);

            $manager->persist($customer);
            $manager->flush();
        }

        

        
    }
}
