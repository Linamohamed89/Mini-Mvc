<?php

namespace MyApp\Controller;

use MyApp\Model\User;
use MyApp\Library\View;

session_start();


class UserController
{
    protected $view;

    public function setView(View $view)
    {
        $this->view = $view;
    }

    public function loginAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $userModel = new User();
            $userData = $userModel->findFirst(array("email" => $email, "password" => $password));
            if ($userData) {
                $_SESSION["user_id"] = $userData->id;
                header("Location: index.php?controller=recipe&action=index");
                exit();
            } else {
                echo "Invalid email or password.";
            }
        }
    }

    public function registerAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $userModel = new User();
            $userModel->setEmail($email);
            $userModel->setPassword($password);
            $userModel->save();
            header("Location: index.php?controller=user&action=login");
            exit();
        }
    }

    public function logoutAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            session_destroy();
            header("Location: index.php?controller=user&action=login");
            exit();
        }
    }
}
