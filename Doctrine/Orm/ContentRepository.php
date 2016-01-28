<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Resolver\ContentCodeResolver;
use Symfony\Cmf\Component\Routing\ContentRepositoryInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\DoctrineProvider;

/**
 * Abstract content repository for ORM.
 *
 * This repository follows the pattern of FQN:id. That is, the full model class
 * name, then a colon, then the id. For example "Acme\Content:12".
 *
 * This will only work with single column ids.
 *
 * @author teito
 */
class ContentRepository extends DoctrineProvider implements ContentRepositoryInterface
{
    private $contentCodeResolver;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param string          $className
     */
    public function __construct(ManagerRegistry $managerRegistry, $className = null, ContentCodeResolver $contentCodeResolver)
    {
        $this->managerRegistry = $managerRegistry;
        $this->className = $className;
        $this->contentCodeResolver = $contentCodeResolver;
    }


    /**
     * {@inheritdoc}
     *
     * @param string $id The content code of the model.
     */
    public function findById($id)
    {
        return $this->contentCodeResolver->getContent($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentId($content)
    {
        if (!is_object($content)) {
            return;
        }

        try {
            $meta = $this->getObjectManager()->getClassMetadata(get_class($content));
            $ids = $meta->getIdentifierValues($content);
            if (1 !== count($ids)) {
                throw new \Exception('Multi identifier values not supported in '.get_class($content));
            }

            return implode(':', array(
                get_class($content),
                reset($ids),
            ));
        } catch (\Exception $e) {
            return;
        }
    }
}
