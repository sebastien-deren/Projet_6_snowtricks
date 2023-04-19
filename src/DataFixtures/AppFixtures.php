<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use App\Enums\FigureTypesEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;


class AppFixtures extends Fixture
{
    public function __construct(private readonly PasswordHasherFactoryInterface $passwordHasher){}
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');
        $sebastien = (new User())
            ->setMail('sebastien.d@gmail.com')
            ->setUsername('sebastienD')
            ->setPhoto("https://avatar.vercel.sh/sebastiend")
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('sebastien'))
            ->setIsVerified(true);
        $manager->persist($sebastien);
        $green =(new User())
            ->setMail('green-s@hotmail.fr')
            ->setUsername('green')
            ->setPhoto("https://avatar.vercel.sh/green")
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('MotDePasse'))
            ->setIsVerified(true);
        $manager->persist($green);



        $mute =(new Figure())
            ->setName('mute')
            ->setDescription('saisie de la carre frontside de la planche entre les deux pieds avec la main avant ')
            //CHANGE THIS WHEN ENUM IS IMPLEMENTED
            ->setCategory(FigureTypesEnum::FLIPS)
            ->setSlug('mute');
        $manager->persist($mute);



        $oneHundredHeighty =(new Figure())
            ->setName('180')
            ->setDescription(' un demi-tour, soit 180 degrés d\'angle ')
            ->setCategory(FigureTypesEnum::ONE_FOOT_TRICK)
            ->setSlug('180');
        $manager->persist($oneHundredHeighty);

        $frontFlip =(new Figure())
            ->setName('Front Flip')
            ->setDescription('Rotation verticale en avant, il est possible de l\'enchainer avec d\'autre flip ou des grabs')
            ->setCategory(FigureTypesEnum::OLD_SCHOOL)
            ->setSlug('front-flip');
        $manager->persist($frontFlip);

        $backsideAir =(new Figure())
            ->setName('Backside Air')
            ->setDescription('LE grab star du snowboard, il consiste a ttraper la carre arrière entre les pieds,et a pousser pour ramener la planche devant')
            ->setCategory(FigureTypesEnum::DESAXED_ROTATION)
            ->setSlug('backside-air');
        $manager->persist($backsideAir);

        $oneFootTrick =(new Figure())
            ->setName('One Foot Trick')
            ->setDescription('Figure réalisé avec un pied décroché de la fixation, afin de tendre la jambe, cette figure est extremement dangeureuse pour les ligaments du genoux')
            ->setCategory(FigureTypesEnum::ROTATIONS)
            ->setSlug('one-foot-trick');
        $manager->persist($oneFootTrick);

       for ($i=0;$i<5;$i++){
           $comment=(new Message())->setContent($faker->text())
               ->setUser(rand(0,1)?$green:$sebastien);
           $manager->persist($comment);
       }



        $manager->flush();
    }
}
