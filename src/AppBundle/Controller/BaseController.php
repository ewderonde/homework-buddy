<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 16-2-2017
 * Time: 20:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Service\AppService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class BaseController extends Controller
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var
     */
    protected $container;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

    /**
     * @var User
     */
    protected $user;

    public function __construct(
        EngineInterface $templating,
        Session $session,
        Router $router,
        TranslatorInterface $translator,
        EntityManager $em,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        ContainerInterface $container,
        AuthenticationUtils $authenticationUtils,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
        $this->em = $em;
        $this->session = $session;
        $this->router = $router;
        $this->translator = $translator;
        $this->container = $container;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->authenticationUtils = $authenticationUtils;
        // Get authenticated user.
        $this->user = $tokenStorage->getToken()->getUser();
    }
}