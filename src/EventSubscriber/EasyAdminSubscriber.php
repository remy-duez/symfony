<?php

namespace App\EventSubscriber;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setTimestampsAndAuthor'],
            BeforeEntityUpdatedEvent::class => ['updatePostTimestamps']
        ];
    }

    public function setTimestampsAndAuthor(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Post || $entity instanceof Comment) {
            if($entity->getCreatedAt() === null){
                $entity->setCreatedAt(new \DateTime('now'));
            }
    
            $user = $this->security->getUser();
            $entity->setAuthor($user);
        }

    }

    public function updatePostTimestamps(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if(!$entity instanceof Post){
            return;
        }
        
        $entity->setUpdatedAt(new \Datetime('now'));
    }
}