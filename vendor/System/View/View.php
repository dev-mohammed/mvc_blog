<?php


namespace System\View;

use System\File;

class View implements ViewInterface
{
    /**
     * File Object
     * @var \System\File
     */
    private $file;
    /**
     * View Path
     * @var string
     */
    private $viewPath;

    /**
     * Passed Data "variables" to the view path
     * @var array
     */
    private $data = [];

    /**
     * The output from the view file
     * @var string
     */
    private $output;

    /**
     * View constructor.
     * @param \System\File $file
     * @param string $viewPath
     * @param array $data
     */
    public function __construct(File $file, $viewPath, array $data)
    {
        $this->file = $file;
        $this->preparePath($viewPath);
        $this->data = $data;
    }

    /**
     * Prepare view path
     * @param string $viewPath
     * @erturn void
     */
    private function preparePath($viewPath)
    {
        $relativeViewPath = 'App/Views/' . $viewPath . '.php';
        $this->viewPath = $this->file->to($relativeViewPath);
        if (!$this->viewFileExists($relativeViewPath)) {
            die('<b>' . $viewPath . ' View<b> dose not exist in Views Folder');
        }
    }

    /**
     * Determine if the view file exists
     * @param string $viewPath
     * @return bool
     */
    private function viewFileExists($viewPath)
    {
        return $this->file->exists($viewPath);
    }

    /**
     * {@inheritDoc}
     */
    public function getOutput()
    {
        if (is_null($this->output)) {
            ob_start();
            extract($this->data);
            require $this->viewPath;
            $this->output = ob_get_clean();
        }
        return $this->output;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->getOutput();
    }
}