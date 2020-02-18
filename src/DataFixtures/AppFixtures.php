<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\City;
use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Governorate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        // facker library
        $faker = Factory::create();
        // variable
        $users = [];
        $categories = [];
        $cities = [];
        $services = [];

        // fix administrateur
        $user = new User();
        $user->setEmail("dhiua99@gmail.com")
            ->setRoles("ROLE_ADMIN")
            ->setPassword($this->encoder->encodePassword($user, '123698745'))
            ->setFirstname("Mohamed Dhia")
            ->setLastname("Hachem");
        $manager->persist($user);

        // fix categories
        $cat = array("Shops", "Hotels", "Restaurants & Café", "Bars", "Events", "Fitness");
        for ($i = 0; $i < count($cat); $i++) {
            $category = new Category();
            $category->setName($cat[$i])
                ->setDescription($cat[$i]);
            $manager->persist($category);
            $categories[] = $category;
        }

        // fix user ROLE_PARTNER
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setRoles("ROLE_PARTNER")
                ->setPassword($this->encoder->encodePassword($user, '123698745'))
                ->setFirstname($faker->firstNameMale)
                ->setLastname($faker->lastName);

            $users[] = $user;

            $manager->persist($user);
        }

        // fix user ROLE_USER
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setRoles("ROLE_USER")
                ->setPassword($this->encoder->encodePassword($user, '123698745'))
                ->setFirstname($faker->firstNameMale)
                ->setLastname($faker->lastName);

            $manager->persist($user);
        }


        $manager->flush();
    }
}
