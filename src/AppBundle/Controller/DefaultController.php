<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Création du msg flashbag
            $msg = "Vous êtes désormais identifié.";
            $request -> getSession()
                     -> getFlashBag()
                     -> add('info', $msg);

            return $this->redirectToRoute('check');
        }

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/macif/check", name="check")
     */
    public function CheckAction(Request $request)
    {
        return $this -> render('default/check.html.twig');
    }

    public function userCheckAction(Request $request)
    {
        // Gestion des services
        $em = $this -> get('doctrine.orm.entity_manager');
        $dataService = $this -> get('service.data');

        // Gestion des variables
        $prenom = $request -> get('prenom');
        $nom = $request -> get('nom');

        $repository = $em -> getRepository('UserBundle:User');

        // On recherche les utilisateurs corespondant exactement à la recherche
        $users = $repository -> getUserByNomPrenom($prenom, $nom);

        if ( count($users) != 0 ) {

            $dataService -> setData($users);

        } else {
            // On recherche s'il y a des utilisateurs approchant
            $users = $repository -> getUserByNomPrenomLike($prenom, $nom);

            if ( count($users) != 0 ) {

                $dataService -> setType(false);
                $dataService -> setData($users);

            } else {
                return new Response("Aucun utilisateur n'a été trouvé !", 404);
            }
        }

        $response = new JsonResponse();
        return $response -> setData(array(
            'type' => $dataService -> getType(),
            'data' => $dataService -> getData()
        ));

    }
}
