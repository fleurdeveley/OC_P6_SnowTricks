<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use PhpParser\Node\Stmt\Foreach_;
use Proxies\__CG__\App\Entity\Category as EntityCategory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $categories = [];
        $categoriesName = ['grabs', 'slides', 'rotations', 'old school'];
        $tricksName = ['mute', 'nose grab', 'japan', 'slide', 'tail slide', '360°', '720°', '1080°', 'backside air', 'method air'];

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
                ->setContent($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400, true))
                ->setCreatedAt($faker->dateTime)
                ->setUpdateAt($faker->dateTime)
                ->setCategory($category);

            $manager->persist($trick);

            // 3 pictures by trick
            for($p = 0; $p < 3; $p++){
                $picture = new Picture;

                $picture->setName($trick->getName() . '' . $p . '.jpg')
                    ->setPath('img/tricks')
                    ->setTrick($trick);
                
                $manager->persist($picture);
            }

            // 2 videos by trick
            for($v = 0; $v < mt_rand(1, 2); $v++){
                $video = new Video;

                $video->setName($trick->getName() . '' . $v . '.jpg')
                    ->setPath('img/tricks')
                    ->setTrick($trick);
                
                $manager->persist($video);
            }
        }

        $manager->flush();
    }
}
