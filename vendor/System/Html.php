<?php


namespace System;


class Html
{
    /**
     * Application Object
     * @var \System\Application
     */
    protected $app;

    /**
     * Html Title
     * @var string
     */
    private $title;

    /**
     * Html Description
     * @var string
     */
    private $description;

    /**
     * Html keywords
     * @var string
     */
    private $keywords;

    /**
     * Html constructor.
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return  void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     * @return void
     */
    public function setKeywords(string $keywords)
    {
        $this->keywords = $keywords;
    }
}