<?php

namespace App\Controller;

use App\Form\FormType;
use App\Entity\Employes;
use App\Repository\EmployesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployesController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('/home.html.twig', [
            'employes' => 'employes',
        ]);
    }

    #[Route('/employes/modifier/{id}', name: 'modifier')]
    #[Route('/employes/form', name:'employes_form' )]

    public function form(Request $request, EntityManagerInterface $manager): Response
    {
        $employes = new Employes;
        $form = $this->createForm(FormType::class, $employes );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($employes);
            $manager->flush();
            return $this->redirectToRoute('tableau');
        }
        // dd($form);
        return $this->render('employes/form.html.twig', [
            'formEmployes' => $form,
            'editMode' => $employes->getId() !== null

        ]);

    }

        #[Route('/employes/tableau', name:'tableau')]
        public function tableau(EmployesRepository $repo) : Response 
        {
            return $this->render('employes/tableau.html.twig', [
                'fichier' => $repo->findAll()
            ]);

        }
        

        #[Route('/employes/supprimer', name:'supprimer')]
        public function supprimer(Employes $employes, EntityManagerInterface $manager)
        {
           $manager->remove($employes);
           $manager->flush();
           return $this->redirectToRoute('tableau');
        }

        // public function modifier(Employes $employes, EntityManagerInterface $manager)
        // {
        //    $manager ->persist(modifier);
        //    $manager-> flush();
        //    return $this->redirectToRoute('tableau');
        // }
}
