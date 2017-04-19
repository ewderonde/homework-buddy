<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 16-2-2017
 * Time: 20:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Service\AgendaService;
use AppBundle\Service\FilterService;
use Doctrine\Common\Util\Debug;
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
     * @var AgendaService
     */
    protected $agendaService;
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var FilterService
     */
    protected $filterService;

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
        AuthorizationCheckerInterface $authorizationChecker,
        AgendaService $agendaService,
        FilterService $filterService,
        \Swift_Mailer $swift_Mailer
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
        $this->agendaService = $agendaService;
        $this->filterService = $filterService;
        $this->mailer = $swift_Mailer;

        // Get authenticated user.
        $this->user = $tokenStorage->getToken()->getUser();

        if($this->user != 'anon.') {
            $this->profile = $this->getCurrentProfile($this->user);
        }

    }

    public function getCurrentProfile (User $user) {
        $userRepo = $this->em->getRepository('AppBundle:User');
        $id = $userRepo->getActiveProfile($user)['id'];

        $profileRepo = $this->em->getRepository('AppBundle:Profile');
        $profile = $profileRepo->findOneBy(['id' => $id]);

        // return profile
        return $profile;
    }
}