<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    /**
     * AppFixtures constructor.
     * @param $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        //$slugify = new Slugify();
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Lamine')
            ->setLastName('Messaci')
            ->setEmail('lamine.messaci@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture('https://unavatar.now.sh/twitter/@messaci')
            ->setIntroduction($faker->sentence())
            ->setDescription("<p>" . join("</p><p>", $faker->paragraphs(3)) . "</p>")
            ->addUserRole($adminRole);

        $manager->persist($adminUser);


        //Nous gérons les utilisateurs
        $users = [];
        //Avatar
        $genres = ['male', 'female'];

        for ($i = 0; $i <= 10; $i++) {
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription("<p>" . join("</p><p>", $faker->paragraphs(3)) . "</p>")
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        //Nous gérons les annonces
        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            // $slug = $slugify->slugify($title);
            //$coverImage = $faker->imageUrl(1000, 350);
            $coverImage = "http://placeimg.com/640/480/arch/grayscale";
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join("</p><p>", $faker->paragraphs(5)) . "</p>";

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                // ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
            //images de chaque annonce
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                //$image->setUrl($faker->imageUrl())
                $image->setUrl("http://placeimg.com/640/480/arch/grayscale")
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
