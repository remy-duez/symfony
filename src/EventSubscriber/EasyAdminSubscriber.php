<?php

namespace App\EventSubscriber;

use App\Entity\Comment;
use App\Entity\Post;
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

    /**
     * This method uses the EventSubscriberInterface to listen to entity being persisted 
     * and set timestamp for creation date and then set user
     *
     * @param BeforeEntityPersistedEvent $event
     * @return void
     */
    public function setTimestampsAndAuthor(BeforeEntityPersistedEvent $event)
    {
        //we get the entity
        $entity = $event->getEntityInstance();


        //if this entity is an instance of post or comment we either set it's creation date (if it hasn't got one already)
        if ($entity instanceof Post || $entity instanceof Comment) {
            if($entity->getCreatedAt() === null){
                $entity->setCreatedAt(new \DateTime('now'));
            }
            
            //we get the user thanks to the core security bundle of Symfony
            $user = $this->security->getUser();
            $entity->setAuthor($user);
        }

    }

    /**
     * This method uses the EventSubscriberInterface to listen to entity being updated 
     * and set timestamp for creation date and then set user
     *
     * @param BeforeEntityUpdatedEvent $event
     * @return void
     */


    public function updatePostTimestamps(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        //as for the moment we only need to update posts we only check if it's a post.
        if(!$entity instanceof Post){
            return;
        }
        
        //and then its update date is hydrated.
        $entity->setUpdatedAt(new \Datetime('now'));
    }
}