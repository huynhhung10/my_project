<?php

namespace App\DataFixtures;

use App\Entity\Novel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HomepageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $novel = new Novel();
        $novel->setName('The Dark Knight');
        $novel->setAuthor('The e');
        $novel->setDescription('Tieu Thuyet');
        $novel->setReleaseYear(2002);

    }
}
