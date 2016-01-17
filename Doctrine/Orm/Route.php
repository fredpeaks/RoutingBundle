<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm;

use Symfony\Cmf\Bundle\RoutingBundle\Resolver\OrmContentCodeResolver;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route as RouteModel;

/**
 * The ORM route version.
 *
 * @author matteo caberlotto <mcaber@gmail.com>
 * @author Wouter J <waldio.webdesign@gmail.com>
 */
class Route extends RouteModel
{
    protected $name;
    protected $position = 0;

    /**
     * The referenced content object
     *
     * @var object
     */
    protected $content;

    /**
     * String coding the content
     * @var string
     */
    protected $contentCode;

    /**
     * Content resolver
     * @var OrmContentCodeResolver
     */
    private $resolver;

    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the string coding the content this url points to
     * @param string $entity
     * @return \Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route
     */
    public function setContentCode($entity)
    {
        $this->contentCode = $entity;

        return $this;
    }

    /**
     * Get the string coding the content this url points to
     * @return string
     */
    public function getContentCode()
    {
        return $this->contentCode;
    }

    /**
     *
     * prepare hashmaps into mapped properties to store them
     */
    public function init($resolver = null)
    {
        if ($resolver) {
            $this->setResolver($resolver);
        }
    }

    /**
     * Returns object attached to route
     *
     * @return null
     */
    public function getContent()
    {
        if (!empty($this->content)) {
            return $this->content;
        }
        if (!empty($this->contentCode)) {
            $this->content = $this->resolver->getContent($this);
            return $this->content;
        }
        return null;
    }

    /**
     * Sets content as attached object and populates
     * 'contentCode' property.
     *
     * @param object $content
     * @param string $field
     * @return Route
     */
    public function setContent($content, $field = 'id')
    {
        $this->content = $content;
        $this->contentCode = $this->resolver->getContentCode($content, $field);

        return $this;
    }

    /**
     * @return OrmContentCodeResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param OrmContentCodeResolver $resolver
     * @return Route
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }
}