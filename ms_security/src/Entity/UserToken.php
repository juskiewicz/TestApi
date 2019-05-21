<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserToken
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository")
 * @ORM\Table("user_data.user_token")
 * @ORM\HasLifecycleCallbacks()
 */
class UserToken extends AbstractEntity
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @Assert\NotNull()
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $token;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotNull()
     */
    private $expiresOn;

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserToken
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return UserToken
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiresOn(): ?DateTime
    {
        return $this->expiresOn;
    }

    /**
     * @param DateTime $expiresOn
     *
     * @return UserToken
     */
    public function setExpiresOn(DateTime $expiresOn): self
    {
        $this->expiresOn = $expiresOn;

        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isExpired(): bool
    {
        return ($this->getExpiresOn() < new DateTime('now'));
    }
}
