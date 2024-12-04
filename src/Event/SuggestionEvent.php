<?php

namespace App\Event;

use App\Entity\Commande;

class SuggestionEvent 
{

     public function __construct(public Commande $commande, public array $suggestions = [])
     {
          
     }

}