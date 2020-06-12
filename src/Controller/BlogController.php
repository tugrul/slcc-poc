<?php

namespace App\Controller;

use App\Form\CommentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    protected static $posts = [
        ['title' => 'Hello World', 'body' => 'It is my first blog post'],
        ['title' => 'Lorem Ipsum', 'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.']
    ];

    /**
     * @Route("/", name="blog_index")
     */
    public function indexAction()
    {
        return $this->render('blog/index.html.twig', [
            'posts' => self::$posts,
        ]);
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function showAction(int $id, Request $request)
    {
        if (!isset(self::$posts[--$id])) {
            throw $this->createNotFoundException('blog post is not exists');
        }

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('blog/show.html.twig', [
                'post' => self::$posts[$id],
            ]);
        }

        $commentForm = $this->createForm(CommentFormType::class);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted()) {
            if ($commentForm->isValid()) {
                $this->addFlash('success', 'Your comment is going to publish after review');
                $commentForm = $this->createForm(CommentFormType::class);
            } else {
                $this->addFlash('danger', 'Your comment is invalid');
            }
        }

        return $this->render('blog/show.html.twig', [
            'post' => self::$posts[$id],
            'commentForm' => $commentForm->createView()
        ]);
    }
}
