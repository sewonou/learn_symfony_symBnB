<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Sewonou')
            ->setLastName('LOKOSSOU')
            ->setEmail('sewonou@symfony.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture('https://avatars.io/twitter/emmanuelokossou')
            ->setIntroduction($faker->sentence)
            ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)).'</p>')
            ->addUserRole($adminRole)
        ;

        $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'female'];
        for($i = 1; $i <=10 ; $i++ ){
            
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99).'.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/').$pictureId ;
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->setPicture($picture)
                ->setHash($hash);
            $manager->persist($user);
            $users[] = $user;
        }

        for($i = 1 ; $i<=30 ; $i++ ){
            $title = $faker->sentence( );
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $description = '<p>'. join('</p><p>', $faker->paragraphs(5)).'</p>';
            $user = $users[mt_rand(0, count($users)-1)];

            $ad = new Ad() ;
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setDescription($description)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user)
            ;
            for($j = 1; $j <= mt_rand(2, 5); $j++ ){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }
            
            for($j = 1 ; $j <= mt_rand(0, 10); $j++){
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                $duration = mt_rand(3, 10);
                $endDate = ( clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration ;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph;
                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment)
                    ;
                $manager->persist($booking);

                if(mt_rand(0, 1)){
                    $comment = new Comment();
                    $comment->setDescription($faker->paragraph())
                        ->setRating(mt_rand(1, 5))
                        ->setAuthor($booker)
                        ->setAd($ad);


                    $manager->persist($comment);
                }

            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
