<?php
/**
 * Created by PhpStorm.
 * User: patmi
 * Date: 02/05/2016
 * Time: 10:03
 */

namespace OC\UserBundle\Controller;


use OC\UserBundle\Entity\Role;
use OC\UserBundle\Entity\Roles;
use OC\UserBundle\Entity\User;
use OC\UserBundle\Type\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/users", name="user_lists")
     */
    public function listUserAction() {
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
    public function supprimerUser(Request $request, User $user) {
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

}