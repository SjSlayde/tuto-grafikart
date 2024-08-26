<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recette', name:'recipe.index')]
    public function index(Request $request): Response
    {
        return $this->render('recipe/index.html.twig');
        // return new Response('Recettes');
    }

    // route on lui indique que dans  url il y aura un des variable en int et text 
    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request , string $slug, int $id): Response
    {   
        return $this->render('recipe/show.html.twig',[
            'slug' => $slug,
            'id' => $id ,
            'person' => [
                'lastname' => 'Delacroix',
                'firstname' => 'Michel'

            ],
            'test' => '<h1> yop </h1>'
        ]);
        
        // retourne un Json 
        // return $this->json([
        // meme chose
        // return new JsonResponse([
        //     'slug' => $slug
        // ]);

        // return new Response('Recette de ' . $slug);
        
        // dd = var_dump plus detailler 
        // dd($slug,$id);
        // dd($request->attributes->get('slug'), $request->attributes->getInt('id'));
    }
}
