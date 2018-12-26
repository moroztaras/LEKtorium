<?php

namespace App\Form\Admin\Model;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleModel
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $text;

    private $image;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $tagsInput;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getTagsInput()
    {
        return $this->tagsInput;
    }

    /**
     * @param mixed $tagsInput
     */
    public function setTagsInput($tagsInput): void
    {
        $this->tagsInput = $tagsInput;
    }



}