<?php declare(strict_types=1);

namespace App\Entity;

use App\Serializer\BaseGroups;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class AbstractEntity
 *
 * @package App\Entity
 */
class AbstractEntity implements EntityInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({
     *     BaseGroups::BASE
     * })
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({
     *     BaseGroups::CREATED_AT
     * })
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({
     *     BaseGroups::MODIFIED_AT
     * })
     */
    protected $modifiedAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return AbstractEntity
     */
    protected function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTimeInterface $modifiedAt
     *
     * @return AbstractEntity
     */
    protected function setModifiedAt(DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     *
     * @throws Exception
     */
    public function setCreated(): void
    {
        $this->setCreatedAt(new DateTime('now'));
        $this->setModifiedAt(new DateTime('now'));
    }

    /**
     * @ORM\PreUpdate
     *
     * @throws Exception
     */
    public function setModified(): void
    {
        $this->setModifiedAt(new DateTime('now'));
    }
}
