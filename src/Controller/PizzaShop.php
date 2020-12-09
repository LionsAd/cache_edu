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
    if (!empty($_GET['ttl'])) {
      $time_to_live = (int) $_GET['ttl'];
    }

    $pizza = \Drupal::service('pizza.oven')->make('margherita');
    \Drupal::cache('pizzas')->set($cid, $pizza, time() + $time_to_live);

    return static::deliver($pizza);
  }

  /**
   * Cleans up margherita pizza in 'pizzas' bin.
   *
   * @return array
   *   A simple renderable array.
   */
  public function simpleCleanupOnePizza__4() {
    \Drupal::cache('pizzas')->delete('pizza:margherita');
    
    return [
      '#markup' => $this->t("'pizza:margherita' cleaned up!"),
    ];
  }


  /**
   * Cleans up all pizzes in 'pizzas' bin.
   *
   * @return array
   *   A simple renderable array.
   */
  public function simpleCleanupAllPizzas__5() {
    \Drupal::cache('pizzas')->deleteAll();
    
    return [
      '#markup' => $this->t("All Pizzas cleaned up!"),
    ];
  }

  /**
   * Returns frozen pizzas page. TTL of 30 days.
   *
   * @return array
   *   A simple renderable array.
   */
  public function frozenPizzas__6() {
    $cid = 'pizza:margherita'; // Cache ID
    $bin = 'frozen_pizzas';
    $time_to_live = 30*24*60*60; // 30 days valid!
    
    $cached_pizza = \Drupal::cache($bin)->get($cid);
    if ($cached_pizza) {
      return static::deliver($cached_pizza->data, static::debugCacheItem($cached_pizza));
    }

    $pizza = \Drupal::service('pizza.maker')->makeFrozen('margherita');
    \Drupal::cache($bin)->set($cid, $pizza, time() + $time_to_live);

    return static::deliver($pizza);
  }

  /**
   * Returns frozen pizzas page. TTL of 30 days.
   *
   * @return array
   *   A simple renderable array.
   */
  public function frozenPizzasTags__7() {
    $cid = 'pizza:marinara'; // Cache ID
    $bin = 'frozen_pizzas';
    $expire = time() + 30*24*60*60; // 30 days valid!
    $cache_tags = ['dough_version'];
    
    $cached_pizza = \Drupal::cache($bin)->get($cid);
    if ($cached_pizza) {
      return static::deliver($cached_pizza->data, static::debugCacheItem($cached_pizza));
    }

    $pizza = \Drupal::service('pizza.maker')->makeFrozen('marinara');
    \Drupal::cache($bin)->set($cid, $pizza, $expire, $cache_tags);

    return static::deliver($pizza);
  }

  public static function debugCacheTags($tags) {
    $keyed = \Drupal::service('database')
      ->query('SELECT tag, invalidations FROM {cachetags} WHERE tag IN ( :tags[] )', [':tags[]' => $tags])
    ->fetchAllKeyed();
    foreach ($tags as $tag) {
      if (empty($keyed[$tag])) {
        $keyed[$tag] = 0;
      }
    }
    return print_r($keyed, TRUE);
  }
 
  public static function debugCacheItem($item) {
    $extra = [];
    $extra[] = '<hr />';
    $extra[] = '<pre>';
    $extra[] = 'TTL (left): ' . ($item->expire - time());
    if (!empty($item->tags)) {
      $extra[] = 'Tags (versions): ' . static::debugCacheTags($item->tags);
    }
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

