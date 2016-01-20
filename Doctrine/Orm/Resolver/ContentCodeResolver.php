<?php

namespace Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Resolver;

class ContentCodeResolver
{
    protected $entityManager;

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setManagerName($entityManagerName)
    {
        $this->entityManager = $entityManager;
    }

    public function getContent($routeObject)
    {
        $content = null;

        $contentCode = $routeObject->getContentCode();

        if (!empty($contentCode)) {
            list($model, $field, $value) = explode(':', $contentCode);
            if (empty($field) or $field === 'id') {
                $content = $this->entityManager->getRepository($model)->find($value);
            } else {
                $finderMethod = 'findOneBy' . ucfirst($field);
                $content = $this->entityManager->getRepository($model)->$finderMethod($value);
            }
        }

        return $content;
    }

    public function getContentCode($content, $field = 'id')
    {
        $getter = 'get' . ucfirst($field);
        $contentCodeArray = array(get_class($content), $field, $content->$getter());

        // omit 'id' as it is implicit
        if ($field === 'id') {
            $contentCodeArray[1] = '';
        }

        return implode(':', $contentCodeArray);
    }
}