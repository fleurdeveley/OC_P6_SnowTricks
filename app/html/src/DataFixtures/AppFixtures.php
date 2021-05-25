<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Avatar($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for($c = 0; $c < 4; $c++){
            $category = new Category;

            $category->setName($faker->categoryName);

            $manager->persist($category);

            for($t = 0; $t < 15; $t++){
                $trick = new Trick;
                $trick->setName($faker->trickName)
                    ->setContent($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true))
                    ->setCreatedAt($faker->dateTime)
                    ->setUpdateAt($faker->dateTime); 
            
                $manager->persist($trick);
            }
        }
        
        $manager->flush();
    }
}
