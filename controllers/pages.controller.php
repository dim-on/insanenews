<?php

class PagesController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Page();
        $this->data = $this->model->getPublishedCategoryList();
        $this->data ['tags_list']= $this->model->getTagsList ();
    }

    public function login () {

    }

    public function registration () {

    }

    public function index () {
        $this->data ['top5commentators'] = $this->model->getTop5CommentatorsList();
        $this->data ['top5_news'] = $this->model->get5LatestNewsByCat($this->model->getPublishedCategoryList());
        $this->data ['categories'] = $this->model->getPublishedCategoryList();
        $this->data ['tags_list']= $this->model->getTagsList ();
        $this->data ['4_latest_news'] = $this->model->get4LatestNews();
    }

    public function category () {
        $alias = App::getRouter()->getParams();

        if (isset($alias[0])) {
            $alias = strtolower($alias[0]);
            $this->data ['news'] = $this->model->getNewsListByAlias($alias);
            $this->data ['tags_list']= $this->model->getTagsList ();
        }

    }

    public function news () {
        $params = App::getRouter()->getParams();

        if (isset($params[0]) && isset($params [1]) ) {

            $category_alias = strtolower($params[0]);
            $id = $params[1];

            $this->data ['news_item'] = $this->model->getNewsByNewsIdCategoryAlias($id, $category_alias);
            $this->data ['comments'] = $this->model->getCommentsByNewsId($id);
            $this->data ['tags'] = $this->model->getTagsByNewsId($id);

        }

    }

    public function comment () {
        $id = App::getRouter()->getParams();
        if (isset($id[0])) {
            $id = $id[0];
            $this->data ['user_comments'] = $this->model->getCommentsByUserId($id);
        }
    }

    public function view () {
        $alias = App::getRouter()->getParams();

        if (isset($alias[0])) {
            $alias = strtolower($alias[0]);

            $this->data ['page'] = $this->model->getByAlias($alias);
        }
    }

    public function admin_index () {
        $this->data['categories'] = $this->model->getCategoryList();
        $this->data['news'] = $this->model->getNewsList();
    }

    public function admin_category_add(){
        if ($_POST){
            $result = $this->model->saveCategory($_POST);
            if ($result) {
                Session::setFlash('Категория сохранена!');
                Session::flash();
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/pages/admin_index/');
        }
    }

    public function admin_category_edit () {

        if ($_POST){
            $id = isset($_POST ['category_id']) ? ($_POST ['category_id']) : null;
            $result = $this->model->saveCategory($_POST, $id);
            if ($result) {
                Session::setFlash('Категория сохранена!');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/pages/admin_index/');
        }

        if (isset($this->params[0])){
            $this->data['categories'] = $this->model->getCategoryById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id!');
            Router::redirect('/pages/admin_index/');
        }
    }

    public function admin_category_delete () {
        var_dump($_POST);
        if (isset($this->params[0])) {
            $result = $this->model->deleteCategory($this->params[0]);
            if ($result) {
                Session::setFlash('Page was deleted!');
            } else {
                Session::setFlash('Error!');
            }
        }
        Router::redirect('/pages/admin_index/');
    }

    public function admin_news_add(){
        if ($_POST){
            $result = $this->model->saveNews($_POST);
            if ($result) {
                Session::setFlash('Страница сохранена!');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/pages/admin_index/');
        }
        $this->data ['categories'] = $this->model->getPublishedCategoryList();
    }

    public function admin_news_edit () {
        $params = App::getRouter()->getParams();

        if ($_POST){
            $id = isset($_POST ['news_id']) ? ($_POST ['news_id']) : null;
            $result = $this->model->saveNews($_POST, $id);
            if ($result) {
                Session::setFlash('Страница сохранена!');
            } else {
                Session::setFlash('Error!');
            }
            Router::redirect('/pages/admin_index/');
        }

        if (isset($params[0]) && isset($params [1]) ) {
            $parent_category = $params[0];
            $id = $params[1];
            $this->data ['news_item'] = $this->model->getNewsByIdParentCategory($id, $parent_category);
        } else {
            Session::setFlash('Wrong page id!');
            Router::redirect('/pages/admin_index/');
        }
        $this->data ['categories'] = $this->model->getPublishedCategoryList();
    }

    public function admin_news_delete () {

        if (isset($this->params[0])) {
            $result = $this->model->deleteNews($this->params[0]);
            if ($result) {
                Session::setFlash('Page was deleted!');
            } else {
                Session::setFlash('Error!');
            }
        }
        Router::redirect('/pages/admin_index/');
    }

}