<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\ProduitRepository;


class ProductController extends AbstractController
{
    private $produitRepository;

    public function __construct(ProduitRepository $produitRepository)
    {
        $this->produitRepository = $produitRepository;
    }

    /**
     * @Route("/boutique", name="boutique")
     */
    public function index(Request $request, ProduitRepository $produitRepository)
    {
        // Récupérer les critères de filtrage
        $categorie = $request->query->get('categorie');
        $cremeNumberCake = $request->query->get('cremeNumberCake');
        $ganacheLayerCake = $request->query->get('ganacheLayerCake');
        $nbPart = $request->query->get('nbPart');

        // Créer une requête de filtrage
        $produits = $produitRepository->findByFilters($categorie, $cremeNumberCake, $ganacheLayerCake, $nbPart);

        return $this->render('product/boutique.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/admin/product/add', name: 'admin_product_add', methods: ['GET', 'POST'])]
    public function ajoutProduit(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // gérer l'exception si quelque chose se passe lors du téléchargement du fichier
                }

                $produit->setImage($newFilename);
            }

            // Dates d'ajout et de modification
            $produit->setDateAjout(new \DateTime());
            $produit->setDateModification(new \DateTime());

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté avec succès!');

            return $this->redirectToRoute('admin_product_add');
        }

        return $this->render('product/add.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }
    public function show(int $id, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/detail.html.twig', [
            'produit' => $produit,
        ]);
    }
     /**
     * @Route("/produit/modifier/{id}", name="product_edit")
     */
    public function edit(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('boutique');
        }

        return $this->render('product/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/supprimer/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('boutique');
    }
}
