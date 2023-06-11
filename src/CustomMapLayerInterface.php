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

  /**
   * Gets the custom map layer group.
   */
  public function getGroup(): string;

  /**
   * Sets the custom map layer group.
   */
  public function setGroup(string $group): CustomMapLayerInterface;

  /**
   * Gets the custom map layer opacity.
   */
  public function getOpacity(): float;

  /**
   * Sets the custom map layer opacity.
   *
   * @param float $opacity
   *   The custom map layer opacity. Float between 0 and 1. Smaller values will be capped at 0, larger at 1.
   */
  public function setOpacity(float $opacity): CustomMapLayerInterface;

  /**
   * Gets the custom map layer options override.
   */
  public function getOptionsOverride(): string;

  /**
   * Sets the custom map layer options override.
   */
  public function setOptionsOverride(string $optionsOverride): CustomMapLayerInterface;

}
