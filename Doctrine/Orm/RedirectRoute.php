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

use LogicException;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Base\BaseRedirectRoute;
use Symfony\Component\Routing\Route as SymfonyRoute;

/**
 * {@inheritdoc}
 *
 * This extends the Route Entity. We need to re-implement everything
 * that the RedirecRoute Model has.
 */
class RedirectRoute extends BaseRedirectRoute
{

}
