<?php declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;

/**
 * Interface EntityInterface
 *
 * @package App\Entity
 */
interface EntityInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface;

    /**
     * @return DateTimeInterface|null
     */
    public function getModifiedAt(): ?DateTimeInterface;
}
