<?php

namespace Application\MainBundle\Admin;

use Application\Sonata\UserBundle\Entity\User;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class PageAdmin extends DefaultAdminWithPosition
{
    protected $supportsPreviewMode = true;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add("title", null, array(
                "empty_data" => "New Page",
            ))
            ->add("content", 'ckeditor', array(
                "empty_data" => '<div class="under-construction"></div>',
                'config_name' => 'extended',
                'label' => false,
            ))
            ->end();
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'preview':
                return 'ApplicationMainBundle:CRUD:preview.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $this->count = $this->getConfigurationPool()->getContainer()
            ->get("doctrine.orm.entity_manager")
            ->getRepository("ApplicationMainBundle:Page")
            ->createNamedQuery("count")
            ->getSingleScalarResult()
        ;

        $listMapper
            ->add("position", null, array("template" => "ApplicationMainBundle:CRUD:list_position.html.twig"))
            ->addIdentifier("title")
            ->add("created_at")
            ->add("updated_at")
            ->add("enabled", null, array("editable" => true))
            ->add("_action", "actions", array(
            "actions" => array(
                "move"     => array("template" => "ApplicationMainBundle:CRUD:list__action_move.html.twig"),
                "view"     => array(),
                "edit"     => array(),
                "delete"   => array(),
            ),
        ));
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add("title")
            ->add("enabled")
            ->add("content", "html")
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add("enabled")
            ->add("title")
            ->add("content")
        ;
    }


    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->end();
    }
}