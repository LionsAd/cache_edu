<?php

namespace Drupal\cache_edu\Factory;

use Drupal\cache_edu\Product\FrozenPizza;

/**
 * Provides a PizzaMaker service.
 */
class PizzaMaker {

  /**
   * Returns hot pizza.
   *
   * @return array
   *   A simple renderable array.
   */
  public function makeFrozen($name) {
    if ($name == 'margherita') {
      return new FrozenPizza('Pizza Margherita', [
        'dough' => ['Flour 00', 'Pinch of Salt', 'Water'],
        'sauce' => ['Custom Made Tomato Sauce'],
        'toppings' => ['Mozarella', 'Basil'],
      ]);
    }

    if ($name == 'marinara') {
      return new FrozenPizza('Pizza Marinara', [
        'dough' => ['Flour 00', 'Pinch of Salt', 'Water'],
        'sauce' => ['Custom Made Tomato Sauce'],
        'toppings' => ['Oregano', 'Garlic', 'Extra virgin olive oil'],
      ]);
    }
    
    throw new \Exception('Pizza not found');
  }
}
