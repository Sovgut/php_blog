<?php

namespace controllers;

use controllers\Controller;
use components\Database\SQLite;
use components\Kernel\App;
use components\Essentials\JsonQueries;
use components\Essentials\Environment;

/**
 * @author Sovgut Sergey
 */
class MainController extends Controller
{
    public function indexAction() : void
    {
        $perPage = $this->app->postsPerPage;
        $offset  = 0;

        if (isset(Environment::Request()->offset)) {
            
            if ((int)Environment::Request()->offset > 0) {
                $offset = Environment::Request()->offset;
            }
        }

        $search = '%%';
        if (isset(Environment::Request()->search)) {
            $search = '%'.Environment::Request()->search.'%';
        }

        $posts = SQLite::Fetch(JsonQueries::Queries()->posts, [
            'offset' => $offset, 'limit' => $perPage, 'search' => $search]);

        $count = (int)SQLite::Fetch(JsonQueries::Queries()->postsCount, ['search' => $search])->count;
        $count /= $count > 0 ? $perPage : 1;

        $this->Render('index', [
            'posts'   => $posts,
            'count'   => $count,
            'offset'  => $offset,
            'perPage' => $perPage,
            'search'  => str_replace('%', '', $search)
        ]);
    }

    public function categoryAction() : void
    {
        $this->Render('category');
    }

    public function postAction() : void
    {
        $this->Render('post');
    }

    public function errorAction() : void
    {
        $this->Render('error');
    }
}
