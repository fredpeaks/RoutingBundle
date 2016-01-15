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

use Symfony\Cmf\Bundle\RoutingBundle\Model\Route as RouteModel;


/**
 * The ORM route version.
 *
 * @author matteo caberlotto <mcaber@gmail.com>
 * @author Wouter J <waldio.webdesign@gmail.com>
 */
class Route extends RouteModel
{
    use RouteEntityTrait;
}
