<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier")
     */
    public function index()
    {
        $em = $this -> getDoctrine()->getManager();

        $panier = $em->getRepository(Panier::class)->findAll();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
        ]);
    }

     /**
     * @Route ("panier/delete/{id}", name="delete_panier")
     */
    public function delete(Panier $panier=null){
        if($panier!=null){

            $pdo = $this-> getDoctrine()->getManager();
            $pdo -> remove($panier);
            $pdo -> flush();

            $this->addFlash("success", "Produit supprimé");
        }
        else {
            $this->addFlash("danger", "Produit introuvable");
        }
        return $this -> redirectToRoute('panier');
    }
}
