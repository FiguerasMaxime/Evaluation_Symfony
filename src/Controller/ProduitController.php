<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\SetQuantityPanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {
        $em = $this -> getDoctrine()->getManager();

        $produit = new Produit();

        $form = $this -> createForm(ProduitType::class, $produit);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $fichier = $form->get('imageUpload')->getData();

            if($fichier){
                $nomFichier = uniqid() .'.'. $fichier->guessExtension();

                try {
                    $fichier->move(
                        $this->getParameter('upload_dir'),
                        $nomFichier
                    );
                }
                catch(FileException $e) {
                    $this->addFlash("danger", 
                    $translator->trans('file.error'));
                    return $this->redirectToRoute('produit');
                }

                $produit->setPhoto($nomFichier);
            }
            $em->persist($produit);
            $em->flush();

            $this->addFlash("success", "Produit ajouté");
        }

        $produits = $em ->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form ->createView(),
        ]);
    }
    /**
     * @Route("/produit/{id}", name="mon_produit")
     */
    public function produit(Request $request, Produit $produit=null){

        if($produit != null){

            $panier = new Panier($produit);
            $form = $this->createForm(SetQuantityPanierType::class, $panier);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($panier);
                $pdo->flush();

                $this->addFlash("success", "Produit ajouté au panier");
                return $this -> redirectToRoute('panier');
            }
            return $this -> render('produit/produit.html.twig', [
                'produit' => $produit,
                'form_setQuantity' => $form->createView()
            ]);
        }
        else{
            //le produit n'existe pas, on redirige l'alternaute
            return $this -> redirectToRoute('produit');
        }
    }

     /**
     * @Route ("produit/delete/{id}", name="delete_produit")
     */
    public function delete(Produit $produit=null){
        if($produit!=null){

            if($produit->getPhoto() != null) {
                unlink($this->getParameter('upload_dir') . $produit->getPhoto());
            }

            $pdo = $this-> getDoctrine()->getManager();
            $pdo -> remove($produit);
            $pdo -> flush();

            $this->addFlash("success", "Produit supprimé");
        }
        else {
            $this->addFlash("danger", "Produit introuvable");
        }
        return $this -> redirectToRoute('produit');
    }
}
