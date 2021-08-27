<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<100; $i++){
            $customer = new Customer();
            $customer->setName('name ' . $i);
            $customer->setEmail('test' . $i . '@gmail.com');
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
