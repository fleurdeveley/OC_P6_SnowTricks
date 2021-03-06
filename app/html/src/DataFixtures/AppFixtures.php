<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $hasher;
    protected $faker;
    protected $users = [];
    protected $categories = [];
    protected $admin;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher)
    {
        $this->slugger = $slugger;
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $manager = $this->categories($manager);
        $manager = $this->user($manager);
        $manager = $this->admin($manager);

        // 1st trick
        $trick = new Trick;

        $trick->setName('Mute')
            ->setContent("
                    Saisir la carre frontside de la planche entre les deux pieds avec la main avant.
                    Un grab consiste à attraper la planche avec la main pendant le saut.
                    Un grab est d'autant plus réussi que la saisie est longue. 
                    Le saut est d'autant plus esthétique que la saisie du snowboard est franche, 
                    ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension 
                    de sa main sur la planche. On dit alors que le grab est tweaké.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 2nd trick
        $trick = new Trick;

        $trick->setName('Nose Grab')
            ->setContent("
                    Saisir la partie avant de la planche, avec la main avant.
                    Un grab consiste à attraper la planche avec la main pendant le saut.
                    Un grab est d'autant plus réussi que la saisie est longue. 
                    Le saut est d'autant plus esthétique que la saisie du snowboard est franche, 
                    ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension 
                    de sa main sur la planche. On dit alors que le grab est tweaké.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 3rd trick
        $trick = new Trick;

        $trick->setName('Japan')
            ->setContent("
                    Saisir l'avant de la planche, avec la main avant, du côté de la carre frontside.
                    Un grab consiste à attraper la planche avec la main pendant le saut.
                    Un grab est d'autant plus réussi que la saisie est longue. 
                    Le saut est d'autant plus esthétique que la saisie du snowboard est franche, 
                    ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension 
                    de sa main sur la planche. On dit alors que le grab est tweaké.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 4th trick
        $trick = new Trick;

        $trick->setName('Slide')
            ->setContent("
                    Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec 
                    La planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins 
                    désaxé.
                    On peut slider avec la planche centrée par rapport à la barre. Celle-ci se situe 
                    approximativement au-dessous des pieds du rideur), mais aussi en nose slide, 
                    c'est-à-dire l'avant de la planche sur la barre, ou en tail slide, l'arrière 
                    de la planche sur la barre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);


        // 5th trick
        $trick = new Trick;

        $trick->setName('Tail Slide')
            ->setContent("
                    Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec 
                    La planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins 
                    désaxé.
                    On peut slider en Tail Slide avec l'arrière de la planche sur la barre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 6th trick
        $trick = new Trick;

        $trick->setName('360°')
            ->setContent("
                    Trois six pour un tour complet. 
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre. 
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 7th trick
        $trick = new Trick;

        $trick->setName('720°')
            ->setContent("
                    Sept deux pour deux tours complets. 
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 8th trick
        $trick = new Trick;

        $trick->setName('1080°')
            ->setContent("
                    ou big foot pour trois tours. 
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 9th trick
        $trick = new Trick;

        $trick->setName('Backside Air')
            ->setContent("
                    Le terme old school désigne un style de freestyle caractérisée par en ensemble 
                    de figure et une manière de réaliser des figures passée de mode, qui fait penser 
                    au freestyle des années 1980 - début 1990.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 10th trick
        $trick = new Trick;

        $trick->setName('Method Air')
            ->setContent("
                    Le terme old school désigne un style de freestyle caractérisée par en ensemble 
                    de figure et une manière de réaliser des figures passée de mode, qui fait penser 
                    au freestyle des années 1980 - début 1990.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 11th trick
        $trick = new Trick;

        $trick->setName('900°')
            ->setContent("
                    deux tours et demi. 
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 12th trick
        $trick = new Trick;

        $trick->setName('Truck Driver')
            ->setContent("
                    Saisir le carre avant et carre arrière avec chaque main (comme tenir un volant 
                    de voiture).
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 13th trick
        $trick = new Trick;

        $trick->setName('270°')
            ->setContent("
                    trois quarts de tours. 
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 14th trick
        $trick = new Trick;

        $trick->setName('630°')
            ->setContent("
                    un tour trois quarts. 
                    On désigne par le mot « rotation » uniquement des rotations horizontales. 
                    Les rotations verticales sont des flips. 
                    Le principe est d'effectuer une rotation horizontale pendant le saut, 
                    puis d'attérir en position switch ou normal.
                    Une rotation peut être frontside ou backside : une rotation frontside correspond 
                    à une rotation orientée vers la carre backside. Cela peut paraître incohérent 
                    mais l'origine étant que dans un halfpipe ou une rampe de skateboard, 
                    une rotation frontside se déclenche naturellement depuis une position frontside, 
                    et vice-versa. Ainsi pour un rider qui a une position regular 
                    (pied gauche devant), une rotation frontside se fait dans le sens inverse des 
                    aiguilles d'une montre.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        // 15th trick
        $trick = new Trick;

        $trick->setName('Seat Belt')
            ->setContent("
                    Saisir le carre frontside à l'arrière avec la main avant. 
                    Un grab consiste à attraper la planche avec la main pendant le saut.
                    Un grab est d'autant plus réussi que la saisie est longue. 
                    Le saut est d'autant plus esthétique que la saisie du snowboard est franche, 
                    ce qui permet au rideur d'accentuer la torsion de son corps grâce à la tension 
                    de sa main sur la planche. On dit alors que le grab est tweaké.
                ")
            ->setSlug(strtolower($this->slugger->slug($trick->getName())))
            ->setCreatedAt($this->faker->dateTime)
            ->setUpdatedAt($this->faker->dateTime)
            ->setCategory($this->faker->randomElement($this->categories))
            ->setUser($this->faker->randomElement($this->users));

        $manager->persist($trick);

        $manager = $this->pictures($trick, $manager);
        $manager = $this->pictures($trick, $manager);
        $manager = $this->videos($trick, $manager);
        $manager = $this->comments($trick, $this->users, $manager);

        $manager->flush();
    }

    // 10 pictures
    private function pictures($trick, $manager)
    {
        $pictures =  [
            '/img/snowtrick1.jpg',
            '/img/snowtrick2.jpg',
            '/img/snowtrick3.jpg',
            '/img/snowtrick4.jpg',
            '/img/snowtrick5.jpg',
            '/img/snowtrick6.jpg',
            '/img/snowtrick7.jpg',
            '/img/snowtrick8.jpg',
            '/img/snowtrick9.jpg',
            '/img/snowtrick10.jpg'
        ];

        // 3 pictures by trick
        for ($p = 0; $p < 3; $p++) {
            $picture = new Picture;

            $picture->setName($trick->getName() . '' . $p . '.jpg')
                ->setSrc($this->faker->randomElement($pictures))
                ->setTrick($trick);

            $manager->persist($picture);
        }

        return $manager;
    }

    // 6 videos
    private function videos($trick, $manager)
    {
        $videos = [
            'https://www.youtube.com/embed/SFYYzy0UF-8',
            'https://www.youtube.com/embed/FuZc3fTmUnc',
            'https://www.youtube.com/embed/8AWdZKMTG3U',
            'https://www.youtube.com/embed/PePNEXh_1N4',
            'https://www.youtube.com/embed/V9xuy-rVj9w',
            'https://www.youtube.com/embed/M_BOfGX0aGs'
        ];

        // 1 à 3 videos by trick
        for ($v = 0; $v < mt_rand(1, 3); $v++) {
            $video = new Video;

            $video->setName($trick->getName() . '' . $v)
                ->setSrc($this->faker->randomElement($videos))
                ->setTrick($trick);

            $manager->persist($video);
        }

        return $manager;
    }

    // 15 comments
    private function comments($trick, $users, $manager)
    {
        $commentsContent =  [
            "Perso, je me suis vautré à chaque fois que j'ai essayé",
            "Top, merci pour ces explications, j'ai réussi la figure",
            "Je n'arrive pas à réaliser la figure.",
            "Les explications sont nulles.",
            "Je ferais mieux de prendre des cours de snowboard.",
            "Je ne comprends pas comment faire.",
            "Bonne chance à tous les débutants.",
            "Ce site est génial.",
            "Le snoowboard : quelles sensations !",
            "Je me suis mis il y a peu de temps, hâtes de pouvoir faire des figures.",
            "Génial, je kiffe !!!",
            "Vivement d'autres figures !!!!",
            "Hâtes d'être à la saison prochaine.",
            "Félicitations !!!",
            "Enfin des explications !!!"
        ];

        // 15 comments by trick
        foreach ($commentsContent as $commentContent) {
            $comment = new Comment;

            $comment->setContent($commentContent)
                ->setCreatedAt($this->faker->dateTime)
                ->setUpdatedAt($this->faker->dateTime)
                ->setUser($this->faker->randomElement($users))
                ->setTrick($trick);

            $manager->persist($comment);
            $comments[] = $comment;
        }

        return $manager;
    }

    private function categories($manager)
    {
        $categoriesName = ['grabs', 'slides', 'rotations', 'old school'];

        // 4 categories
        foreach ($categoriesName as $categoryName) {
            $category = new Category;

            $category->setName($categoryName);

            $manager->persist($category);
            $this->categories[] = $category;
        }

        return $manager;
    }

    private function user($manager)
    {
        $avatar = ['/img/defaultAvatar.png'];

        // 5 users
        for ($u = 0; $u < 5; $u++) {
            $user = new User();

            $user->setEmail("user$u@gmail.com")
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setFullName($this->faker->name())
                ->setAvatar($this->faker->randomElement($avatar))
                ->setRoles(['ROLES_USER'])
                ->setToken(md5(random_bytes(10)))
                ->setActivated(true);

            $manager->persist($user);
            $this->users[] = $user;
        }

        return $manager;
    }

    private function admin($manager)
    {
        $avatar = ['/img/defaultAvatar.png'];

        $admin = new User;

        $admin->setEmail('admin@gmail.com')
            ->setPassword($this->hasher->hashPassword($admin, 'password'))
            ->setFullName('Admin')
            ->setAvatar($this->faker->randomElement($avatar))
            ->setRoles(['ROLES_ADMIN'])
            ->setToken(md5(random_bytes(10)))
            ->setActivated(true);

        $manager->persist($admin);

        $this->admin = $admin;

        return $manager;
    }
}
