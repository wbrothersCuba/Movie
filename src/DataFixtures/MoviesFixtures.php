<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MoviesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
          $movie = new Movie();
          $movie->setTitle("The Dark Knight");
          $movie->setReleaseYear(2008);
          $movie->setDescription('description');
          $movie->setImagePath('https://cdn.pixabay.com/photo/2023/12/04/16/15/ai-generated-8429782_1280.jpg');
          $movie->addActor($this->getReference('actor_1'));
          $movie->addActor($this->getReference('actor_2'));
          $manager->persist($movie);

          $movie2 = new Movie();
          $movie2->setTitle("Avengers");
          $movie2->setReleaseYear(2010);
          $movie2->setDescription('description');
          $movie2->setImagePath('https://cdn.pixabay.com/photo/2022/06/05/11/06/action-figures-7243788_1280.jpg');
          $movie2->addActor($this->getReference('actor_3'));
          $manager->persist($movie2);


          $movie3 = new Movie();
          $movie3->setTitle("Godzilla");
          $movie3->setReleaseYear(2020);
          $movie3->setDescription('description');
          $movie3->setImagePath('https://cdn.pixabay.com/photo/2021/03/16/13/14/cars-6099754_1280.jpg');
          $movie3->addActor($this->getReference('actor_3'));
          $manager->persist($movie3);

        $manager->flush();
    }
}
