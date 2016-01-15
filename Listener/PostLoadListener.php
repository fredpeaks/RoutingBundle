<?php

namespace Symfony\Cmf\Bundle\RoutingBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Cmf\Bundle\RoutingAutoBundle\Doctrine\Orm\AutoRoute;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route;

class PostLoadListener
{
    protected $emBound = false;
    protected $resolver;

    public function __construct($resolver)
    {
        $this->resolver = $resolver;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Route || $entity instanceof AutoRoute) {
            /**
             * This binding is here to avoid circular dependency.
             * Check if resolver is already bound to an entityManager
             * and, in case, assign it.
             */
            if (!$this->emBound) {
                $this->resolver->setEntityManager($entityManager);
                $this->emBound = true;
            }

            $entity->init($this->resolver);
        }
    }
}