<?php

namespace Drupal\cache_edu\Factory;

use Drupal\cache_edu\Product\Pizza;

/**
 * Provides a PizzaOven service.
 */
class PizzaOven {

  /**
   * Returns hot pizza.
   *
   * @return array
   *   A simple renderable array.
   */
  public function make($name) {
    if ($name == 'margherita') {
      return new Pizza('Pizza Margherita#' . (rand() % 100));
    }
    
    throw new \Exception('Pizza not found');
  }
}

