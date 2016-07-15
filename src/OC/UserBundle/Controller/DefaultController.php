<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 02/05/2016
 * Time: 10:03
 */

namespace OC\UserBundle\Controller;


use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use OC\UserBundle\Entity\ChandePassword;
use OC\UserBundle\Entity\Role;
use OC\UserBundle\Entity\Roles;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Type\ChangePasswordType;
use OC\UserBundle\Type\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="oc_user_homepage")
     */
    public function indexAction() {
        return $this->render('MascaEtudiantBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/users", name="user_lists")
     */
    public function listUserAction(Request $request) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        /**
         * @var $users User[]
         */
        $users = $this->getDoctrine()->getManager()
            ->getRepository('OCUserBundle:User')->findAll();
        return $this->render('OCUserBundle:Default:list-users.html.twig', array(
            'users'=> $this->getDoctrine()->getManager()
                            ->getRepository('OCUserBundle:User')->findAll(),
            'roleLabels'=>Roles::$roles
        ));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/role/{id}", name="ajouter_role")
     */
    public function ajouterRoleAction(Request $request, User $user) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $role = new Role();
        $form = $this->createForm(RoleType::class,$role);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $user->addRole($role->getRole());
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('user_lists'));
        }
        return $this->render('OCUserBundle:Default:creer-role.html.twig',[
           'form'=>$form->createView(),
            'user'=>$user
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/suppr/user/{id}",name="supprimer_user")
     */
    public function supprimerUserAction(Request $request, User $user) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous n\'avez pas le droit d\'accès necessaire!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $all_user = $this->getDoctrine()->getManager()->getRepository("OCUserBundle:User")->findAll();
        if(sizeof($all_user) == 1) {
            return $this->render("::message-layout.html.twig",[
                'message'=>'Vous ne pouvez pas supprimer tous les utilisateur!',
                'previousLink'=>$request->headers->get('referer')
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirect($this->generateUrl('user_lists'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modifier/mot-de-passe/{id}", name="modifier_mot_de_passe")
     */
    public function changerMotDePasseAction(Request $request, User $user) {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $changePassword = new ChandePassword();

        $form = $this->createForm(ChangePasswordType::class,$changePassword);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()){
                if($encoder->encodePassword($changePassword->getCurrentPassword(),$user->getSalt()) == $user->getPassword()){
                    $user->setPassword($encoder->encodePassword($changePassword->getNewPassword(), $user->getSalt()));
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirect($this->generateUrl('user_lists'));
                }
                return $this->render('FOSUserBundle:ChangePassword:changePassword.html.twig', array(
                    'form' => $form->createView(),
                    'error_message'=>'L\'ancien mot de passe n\'est pas correct'
                ));
            }

        }

        return $this->render('FOSUserBundle:ChangePassword:changePassword.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/dump", name="backup_db")
     */
    public function createBackUpDbAction() {
        $dump = new \MySQLDump(new \mysqli('localhost','root','','masca_db'));
        $dump->save('sql/sauvegarde_masca_db.sql');
        return $this->render('@OCUser/Default/success-db-backup.html.twig');
    }

}