<?php

namespace App\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use \App\Entity\User;

class UserFixture extends Fixture
{
    /** @inheritDoc */
    public function load(ObjectManager $manager)
    {
        $date = new DateTime();
        foreach ($this->fillData() as [$firstname, $lastname]) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setDatecreate($date->setDate(2019, 2, 3));
            $user->setUpdatedate($date->setDate(2019, 12, 12));
            $manager->persist($user);

        }
        $manager->flush();
    }

    /**
     * @return array
     */
    private function fillData(): array
    {
        return [
            [
                'toto',
                'toto Lastname',
            ],
            [
                'titi',
                'titi Lastname',
            ],
            [
                'tutu',
                'tutu Lastname',
            ],
        ];
    }
}
