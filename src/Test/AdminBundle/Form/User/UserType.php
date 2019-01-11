<?php

namespace Test\AdminBundle\Form\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

class UserType extends AbstractType
{

    //in the controller, they are differently initialised
    private $container;
    /** @var Translator $translator */
    protected $translator;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->translator = $this->container->get('translator');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('username', 'text', array());
        $builder->add('cityName', 'text', array());
        $builder->add('country', 'entity', array(
            'class' => 'TestCoreBundle:Country',
            'label' => 'Country',
            'choice_label' => 'name'));

    }

    public function getName()
    {
        return 'admin_user_edit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('User'),
            'data_class' => 'Test
\CoreBundle\Entity\User'
        ));
    }
}