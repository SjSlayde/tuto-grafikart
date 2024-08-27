<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use app\Entity\Recipe;

class RecipeController extends AbstractController
{
    #[Route('/recette', name:'recipe.index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findWithDurationLowerThan(20);
        // dd($repository->findTotalDuration());

        //trouve le repository souhaiter
        // dd($em->GetRepository(Recipe::class));

        //remplace le titre dans la base de donnée si il est different du nom dans la base de donnée 
        // $recipes[0]->setTitle('Pâte bolognaise');

        // creer une nouvelle entité dans la base de donnée 
        // $recipe = new recipe();
        // $recipe->setTitle('Barbe a papa')
        //         ->setSlug('Barbe-papa')
        //         ->setDuration(5)
        //         ->setContent('Mettez du sucre dans la machine')
        //         ->setCreatedAt(new \DatetimeImmutable())
        //         ->setUpdateAt(new \DatetimeImmutable());

        // $em->persist($recipe);

        // supprime le dernier element dans la table 
        // $em->remove($recipes[0]);

        //necessaire pour faire la requete dans la bdd
        $em->flush();
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
        // return new Response('Recettes');
    }

    // route on lui indique que dans  url il y aura un des variable en int et text 
    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+' , 'slug' => '[a-z0-9-â]+'])]
    public function show(Request $request , string $slug, int $id, RecipeRepository $repository): Response
    {   
        $recipe = $repository->find($id);
        if($recipe->getSlug() != $slug){
            return $this->redirectToRoute('recipe.show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        return $this->render('recipe/show.html.twig',[
            'recipe' => $recipe
            // 'slug' => $slug,
            // 'id' => $id ,
            // 'person' => [
            //     'lastname' => 'Delacroix',
            //     'firstname' => 'Michel'
            // ],
            // 'test' => '<h1> yop </h1>'
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
