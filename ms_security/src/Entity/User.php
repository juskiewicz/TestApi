<?php declare(strict_types=1);

namespace App\Entity;

use App\Serializer\UserGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("user_data.user")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity(
 *     fields={"username"},
 *     groups={
 *         UserGroups::USER_ADD,
 *         UserGroups::USER_EDIT
 *     }
 * )
 */
class User extends AbstractEntity implements UserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     *
     * @Assert\Type(
     *     type="string",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     * @Assert\NotBlank(
     *     message = "Proszę wprowadzić login",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     * @Assert\Length(
     *     min = 3,
     *     max = 100,
     *     minMessage = "Login musi zawierać conajmniej 3 znaki",
     *     maxMessage = "Login może zawierać maksymalnie 64 znaki",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     *
     * @Groups({
     *     UserGroups::USER_LIST,
     *     UserGroups::USER_VIEW,
     *     UserGroups::USER_ADD,
     *     UserGroups::USER_EDIT
     * })
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Assert\NotBlank(
     *     message = "Proszę wprowadzić hasło",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     *
     * @Groups({
     *     UserGroups::USER_ADD,
     *     UserGroups::USER_EDIT
     * })
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({
     *     UserGroups::USER_VIEW,
     *     UserGroups::USER_ADD,
     *     UserGroups::USER_EDIT
     * })
     */
    private $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     *
     * @Assert\Type(
     *     type="string",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     * @Assert\NotBlank(
     *     message = "Proszę wprowadzić imię",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     * @Assert\Length(
     *     min = 3,
     *     max = 64,
     *     minMessage = "Imię musi zawierać conajmniej 3 znaki",
     *     maxMessage = "Imię może zawierać maksymalnie 64 znaki",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     *
     * @Groups({
     *     UserGroups::USER_LIST,
     *     UserGroups::USER_VIEW,
     *     UserGroups::USER_ADD,
     *     UserGroups::USER_EDIT
     * })
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     *
     * @Assert\Type(
     *     type="string",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     * @Assert\NotBlank(
     *     message = "Proszę wprowadzić nazwisko",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     * @Assert\Length(
     *     min = 3,
     *     max = 100,
     *     minMessage = "Nazwisko musi zawierać conajmniej 3 znaki",
     *     maxMessage = "Nazwisko może zawierać maksymalnie 64 znaki",
     *     groups={
     *         UserGroups::USER_ADD,
     *         UserGroups::USER_EDIT
     *     }
     * )
     *
     * @Groups({
     *     UserGroups::USER_LIST,
     *     UserGroups::USER_VIEW,
     *     UserGroups::USER_ADD,
     *     UserGroups::USER_EDIT
     * })
     */
    private $surname;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     *
     * @return User
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
