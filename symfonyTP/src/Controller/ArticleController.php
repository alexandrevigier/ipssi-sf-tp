<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route(path="/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @Route(path="/add")
     */
    public function addArticle(Request $request): Response
    {
        $isOk = false;
        $newArticleForm = $this->createForm(ArticleType::class);
        $newArticleForm->handleRequest($request);
        if($newArticleForm->isSubmitted() && $newArticleForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($newArticleForm->getData());
            $em->flush();
            $isOk = true;
        }
        return $this->render('Article/add.html.twig', [
            'isOk' => $isOk,
            'articleForm' => $newArticleForm->createView(),
            ]);
    }


    /*public function delete(Article $article): Response
    {

    }*/

    /**
     * @return Response
     * @Route(path="/list")
     */
    public function list(): Response
    {
        return new Response('Bonjour');
    }
}