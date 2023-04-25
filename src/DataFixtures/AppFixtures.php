<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\User;
use App\Enums\FigureTypesEnum;
use App\Enums\MediaEnum;
use App\Service\MediaService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;


class AppFixtures extends Fixture
{


    public function __construct(private readonly PasswordHasherFactoryInterface $passwordHasher, private readonly string $fixtureImageFolder,    private string $imageFolder)
    {
    }

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
        $green = (new User())
            ->setMail('green-s@hotmail.fr')
            ->setUsername('green')
            ->setPhoto("https://avatar.vercel.sh/greensora")
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('MotDePasse'))
            ->setIsVerified(true);
        $manager->persist($green);


        $figures = [];
        $mute = (new Figure())
            ->setName('mute')
            ->setDescription('saisie de la carre frontside de la planche entre les deux pieds avec la main avant ')
            //CHANGE THIS WHEN ENUM IS IMPLEMENTED
            ->setCategory(FigureTypesEnum::FLIPS)
            ->setSlug('mute');
        $manager->persist($mute);

        $figures[] = $mute;

        $oneHundredHeighty = (new Figure())
            ->setName('180')
            ->setDescription(' un demi-tour, soit 180 degrés d\'angle ')
            ->setCategory(FigureTypesEnum::ONE_FOOT_TRICK)
            ->setSlug('180');
        $manager->persist($oneHundredHeighty);
        $figures[] = $oneHundredHeighty;

        $frontFlip = (new Figure())
            ->setName('Front Flip')
            ->setDescription('Rotation verticale en avant, il est possible de l\'enchainer avec d\'autre flip ou des grabs')
            ->setCategory(FigureTypesEnum::OLD_SCHOOL)
            ->setSlug('front-flip');
        $manager->persist($frontFlip);
        $figures[] = $frontFlip;

        $backsideAir = (new Figure())
            ->setName('Backside Air')
            ->setDescription('LE grab star du snowboard, il consiste a ttraper la carre arrière entre les pieds,et a pousser pour ramener la planche devant')
            ->setCategory(FigureTypesEnum::DESAXED_ROTATION)
            ->setSlug('backside-air');
        $manager->persist($backsideAir);

        $figures[] = $backsideAir;

        $oneFootTrick = (new Figure())
            ->setName('One Foot Trick')
            ->setDescription('Figure réalisé avec un pied décroché de la fixation, afin de tendre la jambe, cette figure est extremement dangeureuse pour les ligaments du genoux')
            ->setCategory(FigureTypesEnum::ROTATIONS)
            ->setSlug('one-foot-trick');
        $manager->persist($oneFootTrick);
        $figures[] = $oneFootTrick;

        $butter = (new Figure())
            ->setName('Butter')
            ->setDescription('Le butter est la progression naturelle du Nose et du Tail Press, et il est à la base de nombreux tricks freestyle. Il est donc indispensable de le réussir. De son nom anglophone, on peut s’imaginer en train de glisser un couteau dans du beurre pour tes tartines le matin, ce qui laissera forcément une trace dans le beurre. Eh bah dans le contexte du snowboard, le snowboard devient le couteau qui glisse sur la neige. C’est aussi simple que ça !
    Pour réussir cette figure amusante, il s\'agit de soulever le nez ou la queue de la planche dans les airs, tout en restant en contact avec la neige, ce qui te permettra de faire des variations de spin tout en glissant sur la neige. Habituellement, quand tu fais des virages pour changer de direction sur les pistes, tu utiliseras les lames/bords de ton snowboard pour garder le contrôle. Mais pour le Butter, il faut essayer de rester bien centré sur ta planche, car si tu utilises les lames/bords de ton snowboard, tu risques de tomber. Le but est de garder sa planche au plat sur la neige tout en gardant le Nose ou le Tail en l’air, pendant que tu glisses sur la piste.')
            ->setCategory(FigureTypesEnum::GRABS)
            ->setSlug('butter');
        $manager->persist($butter);
        $figures[] = $butter;

        $ollie = (new Figure())
            ->setName('Ollie')
            ->setDescription('Pour faire un Ollie, déplace ton poids sur ta jambe arrière, comme pour les Tails Press, et dans un mouvement rapide, saute en levant ta jambe avant. Puis, avec un peu d\'effort, lève également ta jambe arrière, de sorte que tes pieds soient parallèles et que ta planche soit à l\'horizontale par rapport au sol. ')
            ->setCategory(FigureTypesEnum::OLD_SCHOOL)
            ->setSlug('ollie');
        $manager->persist($ollie);
        $figures[] = $ollie;

        $rotationCork = (new Figure())
            ->setName('Rotation Cork')
            ->setDescription('Le Cork est une variation avancée d\'une rotation, où tu ne te contentes pas de tourner à la verticale, mais où ton corps tourne en fait hors de l\'axe. Au milieu du saut, tu peux faire un 360 en Frontside ou en Backside, pendant que tes jambes et ta planche tournent vers le haut, de sorte que le haut de ton corps se trouve sous ta planche. Pour les experts, l\'exécution du Cork te fera tourner à l\'envers, ce qui est assez fou et difficile à comprendre.')
            ->setCategory(FigureTypesEnum::DESAXED_ROTATION)
            ->setSlug('rotation-cork');
        $manager->persist($rotationCork);
        $figures[] = $rotationCork;

        $tailGrab = (new Figure())
            ->setName('Tail Grab')
            ->setDescription('Le Indy Grab est la figure de base pour saisir sa planche dans les airs, mais il y en a beaucoup d\'autres pour diversifier tes sauts. Pour ceux qui cherchent à impressionner, essayez le Tail Grab comme prochaine étape. Comme pour le Indy Grab, commence par faire un Ollie pour prendre de la hauteur depuis le saut, et une fois en l’air, attrapes la queue de la planche avec ta main arrière. C\'est aussi simple que cela (ou pas si simple, mais personne ne se doutera de rien).')
            ->setCategory(FigureTypesEnum::GRABS)
            ->setSlug('tail-grab');
        $manager->persist($tailGrab);
        $figures[] = $tailGrab;

        $frontSideLipSlide = (new Figure())
            ->setName('Frontslide Lipslide')
            ->setDescription(' Il s\'agit de glisser jusqu\'au rail sur ton côté arrière, puis de sauter dessus avec le nez de la planche au-dessus du rail. Tu atterris avec le rail entre tes fixations, ta planche perpendiculaire à la structure.')
            ->setCategory(FigureTypesEnum::SLIDES)
            ->setSlug('frontside-lipslide');
        $manager->persist($frontSideLipSlide);
        $figures[] = $frontSideLipSlide;

        for ($i = 0; $i < 100; $i++) {
            $comment = (new Message())->setContent($faker->text())
                ->setUser(rand(0, 1) ? $green : $sebastien)
                ->setFigure(rand(0, 1) ? $figures[rand(0, count($figures) - 1)] : null);
            $manager->persist($comment);
        }

        foreach ($figures as $figure) {
            for ($i = 0; $i < rand(2,6); $i++) {
                $media = rand(0,2) ? $this->mediaImageFixture() : $this->mediaVideoFixture();
                $manager->persist($media);
                $figure->addMedium($media);

            }
        }


        $manager->flush();
    }
    private function mediaImageFixture():Media{
        $randPhoto=["1.jpg","2.jpg","3.jpg","4.jpg","5.jpg","6.jpg","7.jpg","8.jpg","9.jpg","10.jpg"];
        $photo= $randPhoto[rand(0,count($randPhoto)-1)];
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir().'/'.rand().$photo;
        $fs->copy($this->fixtureImageFolder.'/'.$photo,$targetPath,true);

        return (new Media())->setFile( new File($targetPath));
    }
    private function mediaVideoFixture():Media{
        $randVideo=[
            "https://www.youtube.com/watch?v=mBB7CznvSPQ",
            "https://www.youtube.com/watch?v=SFYYzy0UF-8",
            "https://www.youtube.com/watch?v=8KotvBY28Mo",
            "https://www.youtube.com/watch?v=PxhfDec8Ays"
        ];
        $video =$randVideo[rand(0,count($randVideo)-1)];
        return (new Media())->setVideo($video);
    }

}


