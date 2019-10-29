<?php

namespace App\Twig;

use Twig\Environment;
use Twig\TwigFunction;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class SidebarExtension extends AbstractExtension
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var PostRepository
     */
    private $postrepository;

    /**
     * @var Environment
     */

    private $twig;

    
    /**
     * @var CacheInterface
     */

    private $cacheInterface;


    public function __construct(PostRepository $postrepository,CommentRepository $commentRepository , Environment $twig, TagAwareAdapterInterface $cacheInterface)
    {
      $this->postrepository = $postrepository;  
      $this->commentRepository = $commentRepository;  
      $this->twig = $twig;  
      $this->cacheInterface = $cacheInterface;  

      
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sidebar',[$this, 'getSidebar'],['is_safe' => ['html']])
        ];
    }

    public function getSidebar(): string
    {   
        return  $this->cacheInterface->get('sidebar', function(ItemInterface $item){

            $item->tag(['comments','posts']);
            return  $this->RenderSidebar();
        });
    }

  
    
    private function RenderSidebar(): string {

      
       $posts =  $this->postrepository->FindForSidebar();
       $comments =  $this->commentRepository->FindForSidebar();
      return  $this->twig->render('partials/sidebar.html.twig',[
            'posts' => $posts,
            'comments' => $comments

        ]);


    }
    
}
