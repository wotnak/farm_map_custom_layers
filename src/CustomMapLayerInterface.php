<?php

declare(strict_types = 1);

namespace Drupal\farm_map_custom_layers;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a custom layer entity type.
 */
interface CustomMapLayerInterface extends ConfigEntityInterface {

  /**
   * Gets the custom map layer title.
   */
  public function getTitle(): string;

  /**
   * Sets the custom map layer title.
   */
  public function setTitle(string $url): CustomMapLayerInterface;

  /**
   * Gets the custom map layer type.
   */
  public function getType(): string;

  /**
   * Sets the custom map layer type.
   */
  public function setType(string $type): CustomMapLayerInterface;

  /**
   * Gets the custom map layer url.
   */
  public function getUrl(): string;

  /**
   * Sets the custom map layer url.
   */
  public function setUrl(string $url): CustomMapLayerInterface;

  /**
   * Checks if the custom map layer is used as a base layer.
   */
  public function isBaseLayer(): bool;

  /**
   * Checks if the custom map layer is used as an overlay.
   */
  public function isOverlay(): bool;

  /**
   * Sets whether the custom map layer should be used as a base layer or overlay.
   */
  public function setBaseLayer(bool $isBaseLayer = TRUE): CustomMapLayerInterface;

}
