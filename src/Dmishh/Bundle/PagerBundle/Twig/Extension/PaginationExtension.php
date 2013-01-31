<?php

/**
 * This file is part of the DmishhPagerBundle package.
 *
 * (c) 2013 Dmitriy Scherbina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmishh\Bundle\PagerBundle\Twig\Extension;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper;

class PaginationExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    public function __construct(RouterHelper $routerHelper)
    {
        $this->routerHelper = $routerHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'pagination' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
            'pagination_url' => new \Twig_Function_Method($this, 'generateUrl', array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders the pagination template
     *
     * @param $pager
     * @param null $route
     * @param string $pageParameterName
     * @param array $queryParams
     * @param string $template
     *
     * @return string
     */
    public function render($pager, $route, $pageParameterName = 'page', array $queryParams = array(), $template = 'DmishhPagerBundle:Pager:pagination.html.twig')
    {
        $data = array(
            'pager' => $pager,
            'route' => $route,
            'queryParams' => $queryParams,
            'pageParameterName' => $pageParameterName
        );

        return $this->environment->render($template, $data);
    }

    public function generateUrl($route, $page, $pageParameterName = 'page', array $queryParams = array())
    {
        $queryParams[$pageParameterName] = $page;

        return $this->routerHelper->generate($route, $queryParams);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'pagination';
    }
}
