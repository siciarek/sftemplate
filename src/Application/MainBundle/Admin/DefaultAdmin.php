<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

class DefaultAdmin extends Admin
{
    protected $maxPerPage = 25;
    protected $maxPageLinks = 10;
    protected $supportsPreviewMode = false;

    public static function ping($host)
    {
        $cmd = sprintf('ping -c 1 -W 5 %s > /dev/null', escapeshellarg($host));
        if (PHP_OS === "WINNT") {
            $cmd = sprintf('ping -n 1 %s', escapeshellarg($host));
        }
        exec($cmd, $res, $rval);
        return $rval === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            'xls',
            'csv',
        );
    }
}