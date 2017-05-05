<?php

namespace AppBundle\Model;
use Symfony\Component\Validator\Constraints as Assert;

class BlgPost
{

  /**
   *  @Assert\NotBlank()
   */
    private $title;

    /**
     *  @Assert\NotBlank()
     */
    private $content;


      /**
       *  @Assert\NotBlank()
       */
    private $publishAt;

        public function setTitle($title)
    {
        $this->title = $title;

    }
    public function getTitle()
    {
      return $this ->title;
      return $this;
    }

    public function setContent($content)
{
    $this->content = $content;

}
public function getContent()
{
  return $this ->content;
  return $this;
}

public function setPublishAt($publishAt)
{
$this->publishAt = $publishAt;

}
public function getPublishAt()
{
return $this ->publishAt;
return $this;
}

  }
