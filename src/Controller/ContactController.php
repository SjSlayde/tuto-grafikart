<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipe;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,MailerInterface $mailer): Response
    {

        // $recipe = new Recipe();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $nom = $data['nom'];
            $prenom = $data['prenom'];
            $email = $data['email'];
            $description = $data['description'];


            $textbody =  "Objet : Demande de renseignements<br>
            
               Bonjour,
        
                Bonjour,
        
                Nous avons reçu une demande de contact de la part de ".$nom." ".$prenom.".
        
                Voici les informations fournies :
            
                Nom : ".$nom."
                Prénom : ".$prenom."
                Adresse e-mail : ".$email."
                Message : ".$description.",
                
                Ta Mere";


            $email = (new Email())
            ->from($email)
            ->to('you@example.com')
            ->subject('Prise de contact')
            ->text($textbody)
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);


            $this->addFlash('success','vous allez être contacter');
        return $this->render('home/index.html.twig');
    } else {
        return $this->render('contact/index.html.twig',[
            'form' => $form
        ]);
    }
}
}
