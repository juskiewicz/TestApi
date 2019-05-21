<?php declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AbstractManager
 *
 * @package App\Manager
 */
abstract class AbstractManager
{
    /**
     * @var bool
     */
    private $flush;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * AbstractManager constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(
        ObjectManager $objectManager
    ) {
        $this->flush = true;
        $this->objectManager = $objectManager;
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }

    /**
     * @return bool
     */
    protected function getFlushMode(): bool
    {
        return $this->flush;
    }

    /**
     * @param bool $mode
     *
     * @return AbstractManager
     */
    public function setFlushMode(bool $mode): self
    {
        $this->flush = $mode;

        return $this;
    }

    protected function softFlush(): void
    {
        if ($this->getFlushMode() === true) {
            $this->getObjectManager()->flush();
        }
    }

    protected function forceFlush(): void
    {
        $this->getObjectManager()->flush();
    }

    /**
     * @param EntityInterface $entity
     */
    protected function updateEntity(EntityInterface $entity): void
    {
        $this->getObjectManager()->persist($entity);
        $this->softFlush();
    }
}
