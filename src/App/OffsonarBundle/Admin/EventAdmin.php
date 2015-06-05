<?php

// src/Acme/DemoBundle/Admin/EventAdmin.php

namespace App\OffsonarBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class EventAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text', array('label' => 'Event Title'))
            ->add('content') //if no type is specified, SonataAdminBundle tries to guess it
            ->add('status')
            ->add('place_name')
            ->add('place_address')
            /*->add('place_image', 'sonata_type_model_list')*/
            ->add('begin_date')
            ->add('end_date')
            ->add('price')
            ->add('price_conditions')
            ->add('promoter')
            ->add('ticketscript')
            /*            */
             /*->add('flyer', 'sonata_type_model') */
            ->add('flyer', 'sonata_type_model_list')
        /* ->add('flyer', 'sonata_type_model_reference') */
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('status')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            /* ->add('content') */
            /* ->add('status') */
            ->add('flyer')
            ->add('begin_date')

            /* ->add('place_address') */
            ->add('place_name')
        ;
    }
}
