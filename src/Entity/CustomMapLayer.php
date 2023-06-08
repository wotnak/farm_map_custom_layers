<?php

declare(strict_types = 1);

namespace Drupal\farm_map_custom_layers\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\farm_map_custom_layers\CustomMapLayerInterface;

/**
 * Defines the custom map layer entity type.
 *
 * @ConfigEntityType(
 *   id = "custom_map_layer",
 *   label = @Translation("Custom map layer"),
 *   label_collection = @Translation("Custom map layers"),
 *   label_singular = @Translation("custom map layer"),
 *   label_plural = @Translation("custom map layers"),
 *   label_count = @PluralTranslation(
 *     singular = "@count custom map layer",
 *     plural = "@count custom map layers",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\farm_map_custom_layers\CustomMapLayerListBuilder",
 *     "form" = {
 *       "add" = "Drupal\farm_map_custom_layers\Form\CustomMapLayerForm",
 *       "edit" = "Drupal\farm_map_custom_layers\Form\CustomMapLayerForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "custom_map_layer",
 *   admin_permission = "manage custom map layers",
 *   links = {
 *     "collection" = "/admin/structure/custom-map-layer",
 *     "add-form" = "/admin/structure/custom-map-layer/add",
 *     "edit-form" = "/admin/structure/custom-map-layer/{custom_map_layer}",
 *     "delete-form" = "/admin/structure/custom-map-layer/{custom_map_layer}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "title",
 *     "status",
 *     "type",
 *     "url",
 *     "isBaseLayer",
 *   }
 * )
 */
class CustomMapLayer extends ConfigEntityBase implements CustomMapLayerInterface {

  /**
   * The custom map layer ID.
   */
  protected string $id;

  /**
   * The custom map layer title.
   */
  protected string $title;

  /**
   * The custom map layer type.
   */
  protected string $type;

  /**
   * The custom map layer url.
   */
  protected string $url;

  /**
   * Determines if the custom map layer should be used as base or overlay.
   */
  protected bool $isBaseLayer;

  /**
   * {@inheritdoc}
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle(string $title): CustomMapLayerInterface {
    $this->title = $title;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return $this->type;
  }

  /**
   * {@inheritdoc}
   */
  public function setType(string $type): CustomMapLayerInterface {
    $this->type = $type;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * {@inheritdoc}
   */
  public function setUrl(string $url): CustomMapLayerInterface {
    $this->url = $url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isBaseLayer(): bool {
    return $this->isBaseLayer ?? TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function isOverlay(): bool {
    return !$this->isBaseLayer();
  }

  /**
   * {@inheritdoc}
   */
  public function setBaseLayer(bool $isBaseLayer = TRUE): CustomMapLayerInterface {
    $this->isBaseLayer = $isBaseLayer;
    return $this;
  }

}
