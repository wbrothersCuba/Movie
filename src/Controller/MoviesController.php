<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class MoviesController extends AbstractController
{
    private $em;
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em){
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route('/movies', name: 'movies')]
    public function index(): Response
    {
        
        return $this->render('movies/index.html.twig', [
             'movies' => $this->movieRepository->findAll()
        ]);
    }
    
    #[Route('/movies/create', name: 'create_movie')]
    #[IsGranted('ROLE_USER', message: 'You are not allowed to access to this page.')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $data = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if($imagePath){
                $newFileName = uniqid().'.'.$imagePath->guessExtension();
                try{
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFileName
                    );
                } catch(FileException $e){
                    return new Response($e->getMessage());
                }
                $data->setUserId($this->getUser()->getId());
                $data->setImagePath('/uploads/'.$newFileName);
            }
            $this->em->persist($data);
            $this->em->flush();
            return $this->redirectToRoute('movies');
        }
        return $this->render('movies/create.html.twig', [
             'form' => $form->createView()
        ]);
    }

    #[Route('/movies/delete/{id}', name: 'delete_movie', methods:['GET', 'DELETE'])]
    #[IsGranted('ROLE_USER', message: 'You are not allowed to access to this page.')]
    public function delete($id): Response
    {
        $movie = $this->movieRepository->find($id);
        if($movie->getUserId() == $this->getUser()->getId())
        {
        $this->em->remove($movie);
        $this->em->flush();
        }
        return $this->redirectToRoute('movies');
    }

    #[Route('/movies/{id}', name: 'show', methods:['GET'])]
    public function show($id): Response
    {
        
        return $this->render('movies/show.html.twig', [
             'movie' => $this->movieRepository->find($id)
        ]);
    }


    #[Route('/movies/edit/{id}', name: 'edit_movie')]
    #[IsGranted('ROLE_USER', message: 'You are not allowed to access to this page.')]
    public function edit($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        if($movie->getUserId() == $this->getUser()->getId())
        {
            /* $movie->setImagePath(
                new File($this->getParameter('kernel.project_dir').'/public/'.$movie->getImagePath())
            );*/
            $form = $this->createForm(MovieFormType::class,$movie);
            $form->handleRequest($request);
            $imagePath = $form->get('imagePath')->getData();
            if($form->isSubmitted() && $form->isValid()){
                if ($imagePath) {
                    // check if the image path is not empty && check if the current file exist
                    if($movie->getImagePath() !== null){
                    /* $fileName= new File($this->getParameter('kernel.project_dir').'/public/'.$movie->getImagePath());
                        if(file_exists($fileName)){
                            $fileName;
                        }*/
                        $newFileName = uniqid().'.'.$imagePath->guessExtension(); 
                        try{
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir').'/public/uploads',
                                $newFileName
                            );
                        } catch(FileException $e){
                            return new Response($e->getMessage());
                        }
                    
                        $movie->setImagePath('/uploads/'.$newFileName); 
                        $this->em->persist($movie);
                        $this->em->flush();
                    // return $this->redirectToRoute('movies');
                }
                } else {
                    $movie->setTitle($form->get('title')->getData());
                    $movie->setReleaseYear($form->get('releaseYear')->getData());
                    $movie->setDescription($form->get('description')->getData());
                    $this->em->persist($movie);
                    $this->em->flush();
                }
            // return $this->redirectToRoute('movies');
            }
            return $this->render('movies/edit.html.twig', [
                'movie' => $movie,
                'form' => $form->createView()
            ]);
        } else{
            return $this->redirectToRoute('movies');
        }
    }
}
