<?php

declare(strict_types = 1);

namespace Drupal\farm_map_custom_layers\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_map\Event\MapRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * FarmOS Custom Map Layers event subscriber.
 */
class MapRenderSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a MapRenderSubscriber object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      MapRenderEvent::EVENT_NAME => ['onMapRender', 100],
    ];
  }

  /**
   * React to the MapRenderEvent.
   *
   * @param \Drupal\farm_map\Event\MapRenderEvent $event
   *   The MapRenderEvent.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function onMapRender(MapRenderEvent $event) {
    /** @var \Drupal\farm_map_custom_layers\CustomMapLayerInterface[] */
    $customMapLayers = $this->entityTypeManager->getStorage('custom_map_layer')->loadByProperties([
      'status' => TRUE,
    ]);
    $settings = array_map(function ($customMapLayer) {
      return $customMapLayer->toArray();
    }, $customMapLayers);
    $event->addBehavior('custom_map_layers', $settings);
  }

}
