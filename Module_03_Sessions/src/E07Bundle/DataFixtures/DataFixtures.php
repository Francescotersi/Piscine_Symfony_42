<?php

namespace App\E07Bundle\DataFixtures;

use App\E03Bundle\Entity\Post;
use App\Entity\User;
use App\E02Bundle\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// crea user con almeno 9 post; (se riesci metti gia dei like/dislike)
// crea almeno un admin;
// crea altri user con almeno 1 post e like/dislike ai post di altri user
// crea user che metta like ai post;

class DataFixtures extends Fixture {

    public function load(ObjectManager $manager) {

    }
}