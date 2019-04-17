<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
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

    /**
     * @param Request $request
     * @param Article $article
     * @Route(path="/edit/{id}")
     */

    public function edit(Request $request, Article $article)
    {

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
        $repository = $this->getDoctrine()->getRepository(Article::class);

        return $this->render('Article/list.html.twig', ['articles' => $repository->findAll()]);
    }
}