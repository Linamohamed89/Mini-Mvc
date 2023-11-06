<?php

namespace MyApp\Model;

use mysqli;
use MyApp\Controller;

abstract class BaseModel
{
    private $conn;

    public function getConnection()
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "recipe_db";
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    /*
    بيرجع اول عنصر بيحقق الشروط ودا بنستخدمه في الوجين مثلا 
    بديله الايميل والباسورد كا براميتر وهوا بيدور في الداتا بيز علي ايميل وباسورد بنفس الى انا ادتهولو
    الفانكشن دي هتلاقيها علي الجت هب علي كود المدرس تبعك 
    لكن فيه غلطه في السطر الى انا هحطلك عليه نجمه 
    بس المهم انا صلحته وشغال خلاص
    */
    public function findFirst($options)
    {
        $model = new static();
        $table = $model->getSource();
        $conn = $this->getConnection();

        $ops = '';

        foreach ($options as $key => $value) {
            // Enclose the value in single quotes
            $ops .= $key . "='" . $value . "' AND "; //*
        }

        // Remove the trailing ' AND ' from the $ops string
        $ops = substr($ops, 0, -5);

        $result = $conn->query("SELECT * FROM " . $table . " where " . $ops);

        while ($row = $result->fetch_object()) {
            $collection = $row;
        }
        return $collection;
    }

    /*
        دي بترجع كل العناصر الموجوده لو مأخدتش براميتر 
        لكن لو اخدت براميتر بترجع العناصر الى بتحتوي علي البراميتر الى هيا وخداه بس 
        وتختلف عن الى فوق ﻻن الى فوق بترجع عنصر واحد بس 
        لكن دي ممكن ترجع اكتر من عنصر عادي 
        بنستخدمها لما احب اعرض كل الاكلات الى عندى
    */
    public function find($options = "")
    {

        $collection = array();

        $model = new static();

        $table = $model->getSource();

        $conn = $this->getConnection();

        $ops = '';

        if (is_array($options)) {
            foreach ($options as $key => $value) {
                $ops .= $key . '=' . $value . ' AND ';
            }
            $ops = substr($ops, 0, -4);

            $ops = 'where ' . $ops;
        }

        $result = $conn->query("SELECT * FROM " . $table . " " . $ops);

        while ($row = $result->fetch_object()) {
            $collection[] = $row;
        }
        return $collection;
    }

    /*
        دي بتعمل كريت للعنصر لو مش موجود ولو موجود بتعمل ابديت 
        انا مستخدمها ف كل حاجه اني اعمل بيها كريت 
        بعمل بيها كريت اكونت في تسجيل الحساب 
        وبعمل بيها ابديت للمنتج لو عايز اعدل عليه في صفحه الايديت 
    */
    public function save()
    {
        $conn = $this->getConnection();

        $table = $this->getSource();

        $thisData = array();

        //kopieren wir die Daten aus $this -> $thisData
        foreach ($this as $key => $value) {
            $thisData[$key] = $value;
        }

        if (isset($thisData["id"])) {
            //Update
            //$values = firstname='$firstname', surename='$surename', email='$email'
            $values = "";

            foreach ($thisData as $key => $value) {

                if (($key != "id") && ($key != "conn")) {

                    $values .= $key . "='" . $value . "', ";

                }
            }

            $values = substr($values, 0, -2);

            $conn->query("UPDATE " . $table . " SET " . $values . " WHERE id=" . $thisData['id']);

        } else {
            //insert
            //$columns = "firstname, surename, email, girokonto_id, sparbuch_id";
            $columns = "";
            //$values = '".$_POST['firstname']."', '".$_POST['surename']."', '".$_POST['email']."', NULL, NULL
            $values = "";

            foreach ($thisData as $key => $value) {

                if (($key != "id") && ($key != "conn")) {

                    $columns .= $key . " , ";

                    $values .= "'" . $value . "', ";
                }
            }

            $columns = substr($columns, 0, -2);

            $values = substr($values, 0, -2);

            $conn->query("INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")");
        }
    }

    /*
        دي بتمسحي بيها منتج او اي حاجه تديها البراميتر وهتمسحو مش شرط منتج 
        لكن انا مستخدمها في اني امسح اكله مثلا
    */
    public function delete($id)
    {
        $conn = $this->getConnection();
        $table = $this->getSource();
        $conn->query("DELETE FROM " . $table . " WHERE id=" . $id);
    }

    abstract public function getSource();

}