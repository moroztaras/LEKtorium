<?php

namespace App\EventSubscriber;

use App\AppEvents;
use App\Event\ArticleEvent;
use App\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FlashListener implements EventSubscriberInterface
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
          AppEvents::ARTICLE_CREATED => 'onArticleFlash',
          AppEvents::ARTICLE_EDIT => 'onArticleEditFlash',
          AppEvents::ARTICLE_DELETE => 'onArticleDeleteFlash',
          AppEvents::USER_CREATED => 'onUserFlash',
          AppEvents::COMMENT_EDIT => 'onCommentEditFlash',
          AppEvents::COMMENT_DELETE => 'onCommentDeleteFlash',
        ];
    }

    public function onArticleEditFlash(ArticleEvent $event)
    {
        $article = $event->getArticle();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('Article %s successfully edited!', $article->getTitle())
        );
    }

    public function onArticleFlash(ArticleEvent $event)
    {
        $article = $event->getArticle();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('New article "%s" successfully added!', $article->getTitle())
        );
    }

    public function onArticleDeleteFlash()
    {
        $this->session->getFlashBag()->add(
          'success',
          sprintf('Article deleted successfully!')
        );
    }

    public function onUserFlash(UserEvent $event)
    {
        $user = $event->getUser();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('New user %s %s successfully added!', $user->getFirstName(), $user->getLastName())
        );
    }

    public function onCommentEditFlash()
    {
        $this->session->getFlashBag()->add(
          'success',
          sprintf('Comment edited successfully!')
        );
    }

    public function onCommentDeleteFlash()
    {
        $this->session->getFlashBag()->add(
          'success',
          sprintf('Comment deleted successfully!')
        );
    }
}
