<?php

declare(strict_types = 1);

namespace Drupal\farm_map_custom_layers;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of custom layers.
 */
class CustomMapLayerListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['title'] = $this->t('Title');
    $header['id'] = $this->t('ID');
    $header['type'] = $this->t('Type');
    $header['status'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\farm_map_custom_layers\CustomMapLayerInterface $entity */
    $row['title'] = $entity->label();
    $row['id'] = $entity->id();
    $row['type'] = $entity->getType();
    $row['status'] = $entity->status() ? $this->t('Enabled') : $this->t('Disabled');
    return $row + parent::buildRow($entity);
  }

}
