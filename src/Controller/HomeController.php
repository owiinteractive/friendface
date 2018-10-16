<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function helloWorld()
    {
        return new Response('<html><body><h1>Hello World</h1></body></html>');
    }
}

?>