<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {

        // $recipe = new Recipe();
        $form = $this->createForm(ContactType::class
        // , $recipe
    );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // $recipe->setCreatedAt(new \DatetimeImmutable())
            //         ->setUpdateAt(new \DatetimeImmutable());
            // $em->persist($recipe);
            // $em->flush();
            $this->addFlash('success','vous allez être contacter');
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form
        ]);
    } else {
        return $this->render('home/index.html.twig');
    }
}
}
