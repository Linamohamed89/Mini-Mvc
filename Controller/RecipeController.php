<?php

namespace MyApp\Controller;

use MyApp\Model\Recipe;
use MyApp\Library\View;

session_start();


class RecipeController
{
    protected $view;

    public function setView(View $view)
    {
        $this->view = $view;
    }


    public function indexAction()
    {
        $recipeModel = new Recipe();
        $collection = $recipeModel->find();
        $this->view->setDataCollection($collection);
    }


    public function createAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'];
            $ingredients = $_POST['ingredients'];
            $instructions = $_POST['instructions'];
            $recipeModel = new Recipe();
            $recipeModel->setTitle($title);
            $recipeModel->setIngredients($ingredients);
            $recipeModel->setInstructions($instructions);
            $recipeModel->save();
            header('Location: index.php?controller=recipe&action=index');
        }
    }

    public function editAction()
    {
        $recipeModel = new Recipe();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $obj = $recipeModel->findFirst(array("id" => $id));
            $this->view->setData($obj);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $ingredients = $_POST['ingredients'];
            $instructions = $_POST['instructions'];
            $recipeModel = new Recipe();
            $recipeModel->setId($id);
            $recipeModel->setTitle($title);
            $recipeModel->setIngredients($ingredients);
            $recipeModel->setInstructions($instructions);
            $recipeModel->save();
            header("Location: index.php?controller=recipe&action=index");
            exit();
        }
    }

    public function viewAction()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $recipeModel = new Recipe();
            $recipe = $recipeModel->findFirst(array("id" => $id));
            $this->view->setData($recipe);
        }
    }

    public function deleteAction(){
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $recipeModel = new Recipe();
            $recipeModel->delete($id);
            header("Location: index.php?controller=recipe&action=index");
        }
    }

    public function saveAction()
    {
        $recipeModel = new Recipe();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $obj = $recipeModel->findFirst(array("id" => $id));
            $this->view->setData($obj);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $ingredients = $_POST['ingredients'];
            $instructions = $_POST['instructions'];
            $recipeModel = new Recipe();
            $recipeModel->setId($id);
            $recipeModel->setTitle($title);
            $recipeModel->setIngredients($ingredients);
            $recipeModel->setInstructions($instructions);
            $recipeModel->save();
            header("Location: index.php?controller=recipe&action=index");
            exit();
        }
    }
}
