<?php

namespace Test\AdminBundle\Service;

use Symfony\Component\Translation\Translator;
use Wap3\AdminBundle\Service\iWap3AdminMenuService;


class AdminMenuService implements iWap3AdminMenuService
{

    protected $container;
    /** @var  Translator $translator */
    protected $translator;


    public function __construct($container)
    {
        $this->container = $container;


        $this->translator = $this->container->get('translator');
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        $request = $this->container->get('request');
        return array(
            'root' => array(
                'administration' => array(
                    'opt' => array(
                        'route' => 'admin_administration',
                        'routeParameters' => array('userId' => $request->get('userId')),
                        'label' => $this->translator->trans('menu_administration')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
                'badwordMessage' => array(
                    'opt' => array(
                        'route' => 'admin_badword_message',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_badword_message')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
                'badwordCorporate' => array(
                    'opt' => array(
                        'route' => 'admin_badword_corporate',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_badword_corporate')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
                'messages' => array(
                    'opt' => array(
                        'route' => 'admin_messages',
                        'routeParameters' => array('messageId' => $request->get('messageId')),
                        'label' => $this->translator->trans('menu_messages')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
                'users' => array(
                    'opt' => array(
                        'route' => 'admin_users',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_users')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
                'moderate' => array(
                    'opt' => array(
                        'route' => 'admin_moderate',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_moderate')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
                'comment' => array(
                    'opt' => array(
                        'route' => 'admin_comment',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_comment')
                    ),
                    'role' => array('ROLE_ADMIN', 'ROLE_ADMIN_USER')
                ),
            ),
            'administration' => array(
                'administration' => array(
                    'opt' => array(
                        'route' => 'admin_administration',
                        'label' => $this->translator->trans('menu_admin_users'),
                        'routeReplace' => 'admin_homepage',
                    ),
                    'role' => array('ROLE_ADMIN')
                )
            ),
            'badWordMessage' => array(
                'badWordMessage' => array(
                    'opt' => array(
                        'route' => 'admin_badword_message',
                        'label' => $this->translator->trans('menu_badword_message'),
                    ),
                    'role' => array('ROLE_ADMIN')
                ),

            ),
            'badWordCorporate' => array(
                'badWordCorporate' => array(
                    'opt' => array(
                        'route' => 'admin_badword_corporate',
                        'label' => $this->translator->trans('menu_badword_corporate'),
                    ),
                    'role' => array('ROLE_ADMIN')
                ),

            ),
            'messages' => array(
                'messages' => array(
                    'opt' => array(
                        'route' => 'admin_messages',
                        'routeParameters' => array('messageId' => $request->get('messageId')),
                        'label' => $this->translator->trans('menu_messages')
                    ),
                    'role' => array('ROLE_ADMIN')
                )
            ),
            'users' => array(
                'users' => array(
                    'opt' => array(
                        'route' => 'admin_users',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_users')
                    ),
                    'role' => array('ROLE_ADMIN')
                )
            ),
            'moderate' => array(
                'moderate' => array(
                    'opt' => array(
                        'route' => 'admin_moderate',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_moderate')
                    ),
                    'role' => array('ROLE_ADMIN')
                )
            ),
            'comment' => array(
                'comment' => array(
                    'opt' => array(
                        'route' => 'admin_comment',
                        'routeParameters' => array('id' => $request->get('id')),
                        'label' => $this->translator->trans('menu_comment')
                    ),
                    'role' => array('ROLE_ADMIN')
                )
            ),

        );
    }
}