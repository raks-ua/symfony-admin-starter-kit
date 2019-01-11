<?php

namespace Test\AdminBundle\Controller;

use Test\AdminBundle\Form\User\CommentType;
use Test\AdminBundle\Form\User\UserType;
use Test\CoreBundle\Entity\BadWord;
use Test\CoreBundle\Entity\User;
use Test\CoreBundle\Service\BadWordService;
use Test\CoreBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wap3\ToolsBundle\Service\LoggerService;


class UserController extends Controller
{

    protected function getFields()
    {
        return array(
            'id' => array(
                'field' => 'id',
                'isSort' => true,
                'filter' => array(
                    'type' => 'like'
                )
            ),
            'alexaUserId' => array(
                'field' => 'alexaUserId',
                'fieldType' => 'htmlcontent',
            ),
            'username' => array(
                'field' => 'username',
                'isSort' => true,
                'filter' => array(
                    'type' => 'like'
                ),
            ),
            'email' => array(
                'field' => 'email',
                'isSort' => true,
                'fieldType' => 'htmlcontent',
            ),
            'city' => array(
                'field' => 'city',
            ),
            'country' => array(
                'field' => 'country',
                'isSort' => true,
            ),
            'data' => array(
                'field' => 'data',
                'fieldType' => 'htmlcontent',

            ),
            'created' => array(
                'field' => 'created',
                'isSort' => true,
                'filter' => array(
                    'type' => 'like'
                ),
            ),
            'actions' => array(
                'class' => 'actions',
                'clearFilters' => true,
                'emptyData' => true
            ),
        );
    }


    public function indexAction($userId = null)
    {
        return $this->render('TestAdminBundle:User:list.html.twig', array(
            'fields' => $this->getFields(),
            'userId' => $userId
            // 'global_action' => $this->getGlobalAction()

        ));
    }

    protected function getGlobalAction()
    {
        return array(
            'new' => array('description' => 'New user',
                'url' => $this->generateUrl('admin_user_new')
            )
        );
    }

    public function listAction()
    {

        /* @var UserService $userService */
        $userService = $this->get('service.user');
        /* @var BadWordService $badWordsService */
        $badWordsService = $this->get('service.badword');

        /* @var LoggerService $loggerService */
        $loggerService = $this->get('service.logger');


        $request = $this->get('request');
        $userId = $request->get('userId');

        $defSort = array(
            'field' => 'id',
            'key' => 'desc'
        );
        $tsqHelper = $this->get('wap3.admin.service.helper.tablequery');
        $params = array(
            'entityName' => 'TestCoreBundle:User'
        );
        $badWordsName = $badWordsService->getBadWordsName();
        $res = $tsqHelper->getQueryResult($request, $this->getFields(), $params, $defSort);

        $d = $res['data'];
        $d['data'] = array();
        if (is_null($userId)) {
            /**@var User $user */
            if (isset($res['qres']) && count($res['qres']) > 0) {
                foreach ($res['qres'] as $user) {
                    if (!is_null($user)) {
                        if (!is_null($user->getData())) {
                            $userData = "<pre>" . print_r($user->getData(), true) . "</pre>";
                        } else {
                            $userData = "";
                        }
                        $country = $user->getCountry();
                        if (!empty($country)) {
                            $countryName = $country->getName();
                        } else {
                            $countryName = '';
                        }

                        if (!is_null($user->getEmail())) {
                            $email = $user->getEmail();
                        } else {
                            $email = '';
                        }
                        try {
                            $d['data'][] = array(
                                'id' => $user->getId(),
                                'username' => $badWordsService->getModifiedText($user->getUsername(), $badWordsName),
                                'alexaUserId' => '<div class="alexa-id">' . $user->getAlexaUserId() . '</div>',
                                'city' => $badWordsService->getModifiedText($user->getCityName(), $badWordsName),
                                'email' => '<div class="user_email">' . $email . '</div>',
                                'data' => $userData,
                                'country' => $countryName,
                                'created' => $user->getCreated()->format('Y-m-d H:i:s'),
                                'canEdit' => true,
                                'canDelete' => true
                            );
                        } catch (\Exception $e) {
                            $loggerService->write('[Id ' . $user->getId() . '][error ' . $e->getMessage() . ']', 'error_admin_users_list');

                        }
                    }
                }
            }
        } else {
            $user = $userService->getUserById($userId);
            if (!is_null($user->getData())) {
                $userData = '<pre>' . print_r(($user->getData())) . '</pre>';
            } else {
                $userData = '';
            }
            $d['data'][] = array(
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'alexaUserId' => '<div class="alexa-id">' . $user->getAlexaUserId() . '</div>',
                'city' => $user->getCityName(),
                'email' => $user->getEmail(),
                'data' => $userData,
                'country' => $user->getCountry()->getName(),
                'created' => $user->getCreated()->format('Y-m-d H:i:s'),
                'canEdit' => true,
                'canDelete' => true
            );

        }


        $d['permissions'] = array();
        $d['fields'] = $this->getFields();
        $d['action_url'] = array(
            'delete' => $this->generateUrl('admin_user_delete'),
            'edit' => $this->generateUrl('admin_user_edit',
                array('id' => '_id_')
            ),
        );
        //$d['global_action'] = $this->getGlobalAction();
        $data = array('ok' => true, 'data' => $d);
        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function registerUserAction(Request $request)
    {
        /** @var UserService $userService */
        $userService = $this->get('service.user');

        $user = new User();

        $form = $this->createForm(new UserType($this->container), $user);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $userService->create($user);
                return new JsonResponse(array('ok' => true));

            }
        }

        $res = $this->render('Wap3AdminBundle:Default:edit.html.twig',
            array('form' => $form->createView(),
                'form_url' => $this->generateUrl('admin_user_new')
            )
        );
        $res->headers->set('Content-Type', 'custom-html-response');
        return $res;


    }

    public function editUserAction($id)
    {

        /** @var UserService $userService */
        $userService = $this->get('service.user');

        $user = $userService->getUserById($id);
        if ($user == NULL) throw $this->createNotFoundException('User is not exists!');
        $form = $this->createForm(new UserType($this->container), $user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $userService->update($user);
                return new JsonResponse(array('ok' => true));
            }
        }
        $res = $this->render('Wap3AdminBundle:Default:edit.html.twig',
            array(
                'form' => $form->createView(),
                'form_url' => $this->generateUrl(
                    'admin_user_edit',
                    array('id' => $id)
                )
            )
        );
        $res->headers->set('Content-Type', 'custom-html-response');
        return $res;

    }

    public function removeUserAction()
    {
        $list = $this->get('request')->request->get('ids');
        /** @var UserService $userService */
        $userService = $this->get('service.user');


        foreach ($list as $id) {
            $user = $userService->getUserById($id);
            $userService->remove($user);
        }
        return new JsonResponse(array('ok' => true));

    }


}
