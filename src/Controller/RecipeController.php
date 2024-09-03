<?php

namespace App\Controller;

use App\Demo;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipe;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeController extends AbstractController
{
    public function __construct(private RecipeRepository $repository){
        
    }

    #[Route('/demo')]
    public function demo (Demo $demo)
    {
        dd($demo);
    }

    #[Route('/recette', name:'recipe.index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $recipes = $this->repository->findWithDurationLowerThan(600);
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
    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+' , 'slug' => '[a-zA-Z0-9-â]+'])]
    public function show(Request $request , string $slug, int $id): Response
    {   
        $recipe = $this->repository->find($id);
        if($recipe->getSlug() != $slug){
            return $this->redirectToRoute('recipe.show', ['id' => $recipe->getId(), 'slug' => strtolower($recipe->getSlug())]);
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
    #[route('/recettes/{id}/edit', name: 'recipe.edit', methods :['GET','POST'])]
    public function edit(int $id,Request $request, EntityManagerInterface $em)
    {
        $recipe = $this->repository->find($id);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        // dd($recipe);
        if ($form->isSubmitted() && $form->isValid()){
            // $recipe->setUpdateAt(new \DatetimeImmutable());
            $em->flush();
            $this->addFlash('success', 'la recette a bien été modifiée');
            return $this->redirectToRoute('recipe.index');
        }
            return $this->render('recipe/edit.html.twig',[
                'recipe' => $recipe,
                'form' => $form
            ]);
    }

    #[route('/recettes/create', name:'recipe.create')]
    public function create(Request $request,EntityManagerInterface $em)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe->setCreatedAt(new \DatetimeImmutable())
                    ->setUpdateAt(new \DatetimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success','La recette a bien été créée');
            return $this->redirectToRoute('recipe.index');
        }
        
        return $this->render('recipe/create.html.twig',[
            'form' => $form
        ]);
        }

        #[route('/recettes/{id}/edit', name:'recipe.remove', methods: ['DELETE'])]
        public function remove(Recipe $recipe,EntityManagerInterface $em)
        {
            $em->remove($recipe);
            $em->flush();
            $this->addFlash('success','La recette a bien été supprimée');
            return $this->redirectToRoute('recipe.index');
        }
}