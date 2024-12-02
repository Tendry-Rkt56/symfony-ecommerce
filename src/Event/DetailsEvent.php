<?php

namespace App\Event;

use App\Entity\Commande;

class DetailsEvent 
{

     public function __construct(public Commande $commande, public array $articles = [])
     {
          
     }

}