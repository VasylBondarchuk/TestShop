<?php

/**
 * Class IndexController
 */
class IndexController extends Controller
{

    /**
     *
     */
    public function IndexAction()
    {
        $this->setTitle("Test shop");
        $this->setView();
        $this->renderLayout();
    }
}