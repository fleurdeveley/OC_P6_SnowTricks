<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
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
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $categories = [];
        $categoriesName = ['grabs', 'slides', 'rotations', 'old school'];
        $tricksName = ['mute', 'nose grab', 'japan', 'slide', 'tail slide', '360°', '720°', '1080°', 'backside air', 'method air'];
        $pictures =  ['/img/snowtrick1.jpg', '/img/snowtrick2.jpg', '/img/snowtrick3.jpg', '/img/snowtrick4.jpg', '/img/snowtrick5.jpg'];
        $videos = ['https://www.youtube.com/embed/SFYYzy0UF-8', 'https://www.youtube.com/embed/FuZc3fTmUnc'];

        // 4 categories
        foreach($categoriesName as $categoryName) {
            $category = new Category;

            $category->setName($categoryName);

            $manager->persist($category);
            $categories [] = $category;
        }

        // 10 tricks
        foreach($tricksName as $trickName) {
            $trick = new Trick;

            $trick->setName($trickName)
                ->setContent($faker->paragraph(15))
                ->setSlug(strtolower($this->slugger->slug($trick->getName())))
                ->setCreatedAt($faker->dateTime)
                ->setUpdatedAt($faker->dateTime)
                ->setCategory($category);

            $manager->persist($trick);

            // 3 pictures by trick
            for($p = 0; $p < 3; $p++){
                $picture = new Picture;

                $picture->setName($trick->getName() . '' . $p . '.jpg')
                    ->setSrc($faker->randomElement($pictures))
                    ->setTrick($trick);
                
                $manager->persist($picture);
            }

            // 2 videos by trick
            for($v = 0; $v < mt_rand(1, 2); $v++){
                $video = new Video;

                $video->setName($trick->getName() . '' . $v)
                    ->setSrc($faker->randomElement($videos))
                    ->setTrick($trick);
                
                $manager->persist($video);
            }
        }

        $manager->flush();
    }
}
