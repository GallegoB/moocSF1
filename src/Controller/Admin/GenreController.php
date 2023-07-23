<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/genre', name: 'app_admin_genre')]
class GenreController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(GenreRepository $genreRepository): Response
    {

        $genres = $genreRepository->findBy([], ['nom' => 'asc']);

        return $this->render('admin/genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    public function ajouter(Request $request, GenreRepository $genreRepository, EntityManagerInterface $entityManager, int $id = null): Response
    {

        if ($request->attributes->get('_route') == 'app_admin_genre_ajouter') {
            $genre = new Genre();
        } else {
            $genre = $genreRepository->find($id);
        }

        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();

            if ($request->attributes->get('_route') == 'app_admin_genre_ajouter') {
                $this->addFlash(
                    'succes',
                    "Le nouveau genre a bien été ajouté !"
                );
            } else {
                $this->addFlash(
                    'succes',
                    "Le nouveau genre a bien été modifié !"
                );
            }

            return $this->redirectToRoute('app_admin_genre');
        }

        return $this->render('admin/genre/editer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(GenreRepository $genreRepository, EntityManagerInterface $entityManager, int $id): Response
    {

        $genre = $genreRepository->find($id);
        $entityManager->remove($genre);
        $entityManager->flush();

        $this->addFlash(
            'succes',
            "Le genre a bien été supprimé !"
        );

        return $this->redirectToRoute('app_admin_genre');
    }
}
