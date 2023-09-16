<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Customer;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\CustomerFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class TaskFixtures extends Fixture
{
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("fr_FR");

        for ($i = 0; $i < 5; $i++) {

            for ($j = 0; $j < $this->faker->randomDigit(); $j++) {

                $task = new Task();
                $task->setName($this->faker->word());
                $task->setContent($this->faker->sentence());
                $manager->persist($task);
                $task->setCustomer($this->getReference(CustomerFixtures::CUSTOMER_EXAMPLE[$i]));

                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return array(
            CustomerFixtures::class,
        );
    }
}
