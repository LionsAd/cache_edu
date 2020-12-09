<?php

namespace Drupal\cache_edu\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides PizzaShop responses.
 */
class PizzaShop extends ControllerBase {

  /**
   * Returns pizza cache page. Clear all caches to re-create.
   * Intentionally broken. ;)
   *
   * @return array
   *   A simple renderable array.
   */
  public function simpleGet__1() {
    $cached_pizza = \Drupal::cache('pizzas')->get('pizza:margherita');
    if ($cached_pizza) {
      return static::deliver($cached_pizza->data);
    }

    $pizza = \Drupal::service('pizza.oven')->make('margherita');
    \Drupal::cache('pizzas')->set('margherita', $pizza);
   
    return static::deliver($pizza);
  }

  /**
   * Returns pizza cache page. Clear all caches to re-create.
   *
   * @return array
   *   A simple renderable array.
   */
  public function simpleGet__2() {
    $cid = 'pizza:margherita'; // Cache ID
    $cached_pizza = \Drupal::cache('pizzas')->get($cid);
    if ($cached_pizza) {
      return static::deliver($cached_pizza->data);
    }

    $pizza = \Drupal::service('pizza.oven')->make('margherita');
    \Drupal::cache('pizzas')->set($cid, $pizza);

    return static::deliver($pizza);
  }

  /**
   * Returns pizza cache page. TTL of 10 seconds.
   *
   * @return array
   *   A simple renderable array.
   */
  public function simpleBestBefore__3() {
    $cid = 'pizza:best-before:margherita'; // Cache ID
    
    $cached_pizza = \Drupal::cache('pizzas')->get($cid);
    if ($cached_pizza) {
      return static::deliver($cached_pizza->data, static::debugCacheItem($cached_pizza));
    }

    $time_to_live = 10; // 10 seconds valid

    $pizza = \Drupal::service('pizza.oven')->make('margherita');
    \Drupal::cache('pizzas')->set($cid, $pizza, time() + $time_to_live);

    return static::deliver($pizza);
  }

  public static function debugCacheItem($item) {
    $extra = [];
    $extra[] = '<hr />';
    $extra[] = '<pre>';
    $extra[] = 'TTL (left): ' . ($item->expire - time());
    $extra[] = '';
    foreach ($item as $key => $value) {
      $extra[] = "$key: " . json_encode($value);
    }
    $extra[] = '</pre>';
    return implode('<br />', $extra);
  }

  public static function deliver(object $pizza, $extra = '') {
    \Drupal::service('page_cache_kill_switch')->trigger();
    return [
      '#markup' => (string) $pizza . $extra,
    ];
  }
}

