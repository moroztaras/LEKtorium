<?php

namespace App;

final class AppEvents
{
    const ARTICLE_CREATED = 'article.createdAt';
    const ARTICLE_EDIT = 'article.edit';
    const ARTICLE_DELETE = 'article.delete';
    const USER_CREATED = 'user.createdAt';
    const USER_EDIT = 'user.edit';
    const USER_DELETE = 'user.delete';
    const COMMENT_EDIT = 'comment.edit';
    const COMMENT_DELETE = 'comment.delete';
}
