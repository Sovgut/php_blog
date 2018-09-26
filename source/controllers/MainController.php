<?php

namespace controllers;

use controllers\Controller;
use components\Database\Query;
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
        $request = Environment::Request();
        $perPage = $this->app->postsPerPage;
        $offset  = 0;

        if (isset($request->offset)) 
            if ((int)$request->offset > 0) 
                $offset = $request->offset;

        $search = '%%';
        if (isset($request->search))
            $search = '%'.$request->search.'%';

        Environment::AddLog(JsonQueries::Queries());

        $posts = Query::Fetch(JsonQueries::Queries()->posts, [
            'offset' => (int)$offset, 'limit' => (int)$perPage, 'search' => $search]);

        $count = (int)Query::Fetch(JsonQueries::Queries()->postsCount, ['search' => $search])->count;
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
        $request = Environment::Request();

        if (!isset($request->id)) {
            Environment::Redirect('index');
        }

        $perPage = $this->app->postsPerPage;
        $offset  = 0;

        if (isset($request->offset)) 
            if ((int)$request->offset > 0) 
                $offset = $request->offset;

        $posts = Query::Fetch(JsonQueries::Queries()->categoryPosts, 
                ['id' => $request->id, 'offset' => $offset, 'limit' => $perPage]);
        $count = Query::Fetch(JsonQueries::Queries()->categoryPostsCount, ['id' => $request->id])[0]->count;
        $count /= $count > 0 ? $perPage : 1;

        //Environment::AddLog($posts);

        $this->Render('category', [
            'posts'   => $posts,
            'count'   => $count,
            'offset'  => $offset,
            'perPage' => $perPage,
            'id'      => $request->id
        ]);
    }

    public function postAction() : void
    {
        $request = Environment::Request();

        if (!isset($request->id)) {
            Environment::Redirect('index');
        }

        $post = Query::Fetch(JsonQueries::Queries()->post, ['id' => $request->id])[0];
        if (empty($post)) {
            Environment::Redirect('index');
        }

        $comments = Query::Fetch(JsonQueries::Queries()->comments, ['id' => $request->id]);

        $this->Render('post', [
            'post'     => $post,
            'comments' => $comments
        ]);
    }

    public function addpostAction() : void 
    {
        $request = Environment::Request();
        $info = pathinfo($request->image['name']);

        $output = md5(strtolower($info['filename'].date("Y-m-d H:i:s"))).'.'.$info['extension'];
        $uploadfile = Environment::Dir()->public->postsImages.basename($output);

        if (move_uploaded_file($request->image['tmp_name'], $uploadfile)) {
            Query::Execute(
                "INSERT INTO posts (category_id, published_date, path_img, title, content, authorName)".
                "VALUES (:categoryID, :date, :image, :title, :content, :author)", [
                        'categoryID' => $request->category, 
                        'date' => date('YYYY-mm-dd HH:mm:ss'),
                        'image' => $uploadfile,
                        'title' => $request->title,
                        'content' => $request->content,
                        'author' => 'Sovgut Sergey'
                    ]
            );

            Environment::Redirect('index');
        }

        Environment::AddLog($request);
    }

    public function errorAction() : void
    {
        $this->Render('error');
    }
}
