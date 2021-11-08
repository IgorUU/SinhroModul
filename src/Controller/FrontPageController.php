<?php

namespace Drupal\sinhromodul\Controller;

use Drupal\Core\Controller\ControllerBase;

class FrontPageController extends ControllerBase 
{
    public function frontpage() {
        return [
            '#markup' => 'Ovo je početna strana!'
        ];
    }
}