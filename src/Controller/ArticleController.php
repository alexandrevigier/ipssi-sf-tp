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
     * @return Response
     * @Route(path="/edit/{id}")
     */

    public function edit(Request $request, Article $article): Response
    {
        $isOk = false;
        $newArticleForm = $this->createForm(ArticleType::class, $article);
        $newArticleForm->handleRequest($request);
        if($newArticleForm->isSubmitted() && $newArticleForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $isOk = true;
        }

        return $this->render('Article/edit.html.twig', [
            'articleForm' => $newArticleForm->createView(),
            'isOk' => $isOk,
        ]);
    }

    /**
     * @Route(path="/delete/{id}")
     */
    public function delete(Article $article): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('app_article_list');
    }

    /**
     * @return Response
     * @Route(path="/list/{page}", defaults={"page"=1})
     */
    public function list($page): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        if ($page == 1){
            $articles = $repository->getArticles(0);
        }else{
            $articles = $repository->getArticles(($page - 1) * 10);
        }

        return $this->render('Article/list.html.twig', ['articles' => $articles, 'page' => $page, 'isOk' => true]);
    }

    /**
     * @return Response
     * @param Article $article
     * @Route(path="/view/{id}")
     */
    public function viewArticle(Article $article): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $viewArticle = $repository->find($article);
        if (!empty($viewArticle)){
            return $this->render('Article/view.html.twig', ['article' => $viewArticle]);
        }
        return $this->redirectToRoute('app_article_list', ['articles' => $repository->findAll(), 'isOk' => false]);
    }
}