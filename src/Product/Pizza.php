<?php

namespace Drupal\cache_edu\Product;

class Pizza {

  protected $name;

  /**
   * Constructs a new pizza.
   *
   * @param string $name
   */
  function __construct($name) {
    $this->name = $name;
  }

  /**
   * Returns a pizza as string.
   *
   * @return string
   */ 
  function __toString() {
    return '<p>' . $this->name . '</p>';
  }
}
