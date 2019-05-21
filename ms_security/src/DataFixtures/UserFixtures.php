<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    private const USERNAME = 'username';
    private const NAME = 'name';
    private const SURNAME = 'surname';
    private const PASSWORD = 'password';

    private const USERS = [
        [
            self::USERNAME => 'juskiewicz',
            self::NAME => 'Tomasz',
            self::SURNAME => 'Juśkiewicz',
            self::PASSWORD => 'demo1'
        ],[
            self::USERNAME => 'goly',
            self::NAME => 'Paweł',
            self::SURNAME => 'Gołdyn',
            self::PASSWORD => 'demo2'
        ],[
            self::USERNAME => 'moni',
            self::NAME => 'Monika',
            self::SURNAME => 'Bednarska',
            self::PASSWORD => 'demo3'
        ],[
            self::USERNAME => 'barszcz',
            self::NAME => 'Izabela',
            self::SURNAME => 'Bartczak',
            self::PASSWORD => 'demo4'
        ]
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = $this->createUser(
                $userData[self::USERNAME],
                $userData[self::NAME],
                $userData[self::SURNAME],
                $userData[self::PASSWORD]
            );

            $manager->persist($user);
            $this->setReference(sprintf('user_%s', $user->getId()), $user);
        }

        $manager->flush();
    }

    /**
     * @param string $username
     * @param string $name
     * @param string $surname
     * @param string $password
     *
     * @return User
     */
    private function createUser(
        string $username,
        string $name,
        string $surname,
        string $password
    ): User {
        $user = new User();
        $user
            ->setUsername($username)
            ->setName($name)
            ->setSurname($surname)
            ->setRoles(['ROLE_USER'])
            ->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            )
        ;

        return $user;
    }
}
