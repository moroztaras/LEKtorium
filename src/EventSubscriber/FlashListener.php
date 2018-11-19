<?php

namespace App\EventSubscriber;

use App\AppEvents;
use App\Event\ArticleEvent;
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
          AppEvents::ARTICLE_CREATED => 'onFlash'
        ];
    }
    public function onFlash(ArticleEvent $event)
    {
        $article = $event->getArticle();
        $this->session->getFlashBag()->add(
          'success',
          sprintf('new article "%s" successfully added!',$article->getTitle())
        );
    }
}
