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
          AppEvents::USER_CREATED => 'onUserCreateFlash',
          AppEvents::USER_EDIT => 'onUserEditFlash',
          AppEvents::USER_CHANGE_ROLE => 'onUserChangeRoleFlash',
          AppEvents::USER_DELETE => 'onUserDeleteFlash',
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
          sprintf('Article was successfully deleted!')
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
          sprintf('Comment was successfully deleted!')
        );
    }

    public function onUserCreateFlash(UserEvent $event)
    {
        $user = $event->getUser();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('New user %s %s successfully added!', $user->getFirstName(), $user->getLastName())
        );
    }

    public function onUserEditFlash(UserEvent $event)
    {
        $user = $event->getUser();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('User %s %s successfully edit!', $user->getFirstName(), $user->getLastName())
        );
    }

    public function onUserChangeRoleFlash(UserEvent $event)
    {
        $user = $event->getUser();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('Role user successfully changed!')
        );
    }

    public function onUserDeleteFlash(UserEvent $event)
    {
        $user = $event->getUser();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('User %s %s was successfully deleted!', $user->getFirstName(), $user->getLastName())
        );
    }
}
