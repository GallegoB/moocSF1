<?php

namespace App\Controller\Admin;

use App\Entity\Jeu;
use App\Form\JeuType;
use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/jeu', name: 'app_admin_jeu')]
class JeuController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(JeuRepository $jeuRepository): Response
    {

        $Jeux = $jeuRepository->findBy([], ['nom' => 'asc']);

        return $this->render('admin/jeu/index.html.twig', [
            'jeux' => $Jeux,
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    public function ajouter(Request $request, JeuRepository $jeuRepository, EntityManagerInterface $entityManager, int $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_admin_jeu_ajouter') {
            $Jeu = new Jeu();
        } else {
            $Jeu = $jeuRepository->find($id);
        }

        $form = $this->createForm(JeuType::class, $Jeu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Jeu);
            $entityManager->flush();

            if ($request->attributes->get('_route') == 'app_admin_jeu_ajouter') {
                $this->addFlash(
                    'succes',
                    "Le nouveau Jeu a bien été ajouté !"
                );
            } else {
                $this->addFlash(
                    'succes',
                    "Le nouveau Jeu a bien été modifié !"
                );
            }

            return $this->redirectToRoute('app_admin_jeu');
        }

        return $this->render('admin/Jeu/editer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(JeuRepository $JeuRepository, EntityManagerInterface $entityManager, int $id): Response
    {

        $Jeu = $JeuRepository->find($id);
        $entityManager->remove($Jeu);
        $entityManager->flush();

        $this->addFlash(
            'succes',
            "Le Jeu a bien été supprimé !"
        );

        return $this->redirectToRoute('app_admin_jeu');
    }
}
