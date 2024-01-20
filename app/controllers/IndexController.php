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
        // редирект на сторінку категорій
        Helper::redirect('/category/list'); 
    }
}