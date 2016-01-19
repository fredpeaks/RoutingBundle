<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\RoutingBundle\Admin\Orm;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route;
use Symfony\Cmf\Bundle\RoutingBundle\Util\Sf2CompatUtil;

class RouteAdmin extends Admin
{
    protected $translationDomain = 'CmfRoutingBundle';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('staticPrefix')
            ->add('variablePattern')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('staticPrefix')
            ->add('variablePattern')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general', array(
                'translation_domain' => 'CmfRoutingBundle',
            ))
                ->add('staticPrefix')
                ->add('variablePattern')
            ->end()
            ->with('form.group_advanced', array(
                'translation_domain' => 'CmfRoutingBundle',
            ))
                ->add('variablePattern', Sf2CompatUtil::getFormTypeName('text'), array('required' => false), array('help' => 'form.help_variable_pattern'))
                ->add(
                    'defaults',
                    Sf2CompatUtil::getFormTypeName('sonata_type_immutable_array'),
                    array('keys' => $this->configureFieldsForDefaults($this->getSubject()->getDefaults()))
                )
                ->add(
                    'options',
                    Sf2CompatUtil::getFormTypeName('sonata_type_immutable_array'),
                    array('keys' => $this->configureFieldsForOptions($this->getSubject()->getOptions())),
                    array('help' => 'form.help_options')
                )
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('staticPrefix')
            ->add('variablePattern')
        ;
    }

    public function getExportFormats()
    {
        return array();
    }

    /**
     * Provide default route defaults and extract defaults from $dynamicDefaults.
     *
     * @param array $dynamicDefaults
     *
     * @return array Value for sonata_type_immutable_array
     */
    protected function configureFieldsForDefaults($dynamicDefaults)
    {
        $defaults = array(
            '_controller' => array('_controller', Sf2CompatUtil::getFormTypeName('text'), array('required' => false)),
            '_template' => array('_template', Sf2CompatUtil::getFormTypeName('text'), array('required' => false)),
            'type' => array('type', Sf2CompatUtil::getFormTypeName('cmf_routing_route_type'), array(
                'empty_value' => '',
                'required' => false,
            )),
        );

        foreach ($dynamicDefaults as $name => $value) {
            if (!isset($defaults[$name])) {
                $defaults[$name] = array($name, Sf2CompatUtil::getFormTypeName('text'), array('required' => false));
            }
        }

        //parse variable pattern and add defaults for tokens - taken from routecompiler
        /** @var $route Route */
        $route = $this->subject;
        if ($route && $route->getVariablePattern()) {
            preg_match_all('#\{\w+\}#', $route->getVariablePattern(), $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach ($matches as $match) {
                $name = substr($match[0][0], 1, -1);
                if (!isset($defaults[$name])) {
                    $defaults[$name] = array($name, Sf2CompatUtil::getFormTypeName('text'), array('required' => true));
                }
            }
        }

        if ($route && $route->getOption('add_format_pattern')) {
            $defaults['_format'] = array('_format', Sf2CompatUtil::getFormTypeName('text'), array('required' => true));
        }
        if ($route && $route->getOption('add_locale_pattern')) {
            $defaults['_locale'] = array('_locale', Sf2CompatUtil::getFormTypeName('text'), array('required' => false));
        }

        return $defaults;
    }

    /**
     * Provide default options and extract options from $dynamicOptions.
     *
     * @param array $dynamicOptions
     *
     * @return array Value for sonata_type_immutable_array
     */
    protected function configureFieldsForOptions(array $dynamicOptions)
    {
        $checkboxType =  Sf2CompatUtil::getFormTypeName('checkbox');

        $options = array(
            'add_locale_pattern' => array('add_locale_pattern', $checkboxType, array('required' => false, 'label' => 'form.label_add_locale_pattern', 'translation_domain' => 'CmfRoutingBundle')),
            'add_format_pattern' => array('add_format_pattern', $checkboxType, array('required' => false, 'label' => 'form.label_add_format_pattern', 'translation_domain' => 'CmfRoutingBundle')),
            'add_trailing_slash' => array('add_trailing_slash', $checkboxType, array('required' => false, 'label' => 'form.label_add_trailing_slash', 'translation_domain' => 'CmfRoutingBundle')),
        );

        foreach ($dynamicOptions as $name => $value) {
            if (!isset($options[$name])) {
                $options[$name] = array($name, 'text', array('required' => false));
            }
        }

        return $options;
    }

    public function prePersist($object)
    {
        $defaults = array_filter($object->getDefaults());
        $object->setDefaults($defaults);
    }

    public function preUpdate($object)
    {
        $defaults = array_filter($object->getDefaults());
        $object->setDefaults($defaults);
    }

    public function toString($object)
    {
        return $object instanceof Route && $object->getId()
            ? $object->getId()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
            ;
    }
}
