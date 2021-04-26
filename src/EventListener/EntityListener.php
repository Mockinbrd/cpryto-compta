<?php

namespace App\EventListener;

use App\Entity\Portfolio;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class EntityListener
{
    private const CREATED_AT_FIELD = "created_at";
    private const UPDATED_AT_FIELD = "updated_at";

    private SluggerInterface $slugger;
    private Security $security;

    public function __construct(SluggerInterface $slugger, Security $security)
    {
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (property_exists($entity, self::CREATED_AT_FIELD)) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

        if (property_exists($entity, self::UPDATED_AT_FIELD)) {
            $entity->setUpdatedAt(new \DateTime());
        }

        if ($entity instanceof Portfolio) {
            /* @var $entity Portfolio */
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
            if ($entity->getUser()) return;
            elseif ($this->security->getUser()) $entity->setUser($this->security->getUser());
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (property_exists($entity, self::UPDATED_AT_FIELD)) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (property_exists($entity, self::UPDATED_AT_FIELD)) {
            $entity->setUpdatedAt(new \DateTime());
        }

        if ($entity instanceof Portfolio) {
            $entity->setSlug($this->slugger->slug($entity->getName()));
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (property_exists($entity, self::UPDATED_AT_FIELD)) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}