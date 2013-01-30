<?php

namespace Dmishh\PagerBundle\Twig\Extension;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper;
use Symfony\Component\Translation\TranslatorInterface;

class PaginationExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    public function __construct(RouterHelper $routerHelper, TranslatorInterface $translator)
    {
        $this->routerHelper = $routerHelper;
        $this->translator = $translator;
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
//            'knp_pagination_sortable' => new \Twig_Function_Method($this, 'sortable', array('is_safe' => array('html'))),
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'pagination';
    }
}
