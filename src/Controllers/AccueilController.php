<?php

class AccueilController
{
    public function index(): void
    {
        Flight::render('accueil', ['title' => 'Accueil']);
    }
}