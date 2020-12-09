<?php

namespace Drupal\cache_edu\Product;

class FrozenPizza {

  protected $name;
  protected $ingredients;

  /**
   * Constructs a new frozen pizza.
   *
   * @param object $ingredients
   */
  function __construct($name, $ingredients) {
    // Give it a unique ID.
    $name = $name . '#' . (rand() % 100);
    $this->name = $name;
    $this->ingredients = $ingredients;
  }

  /**
   * Returns a pizza as string.
   *
   * @return string
   */ 
  function __toString() {
    $ingredientsString = print_r($this->ingredients, TRUE);
    return '<p>' . $this->name . '</p><br /><pre>' . $ingredientsString . '</pre>';
  }
}
