<?php

declare(strict_types = 1);

namespace Drupal\farm_map_custom_layers\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Custom layer form.
 *
 * @property \Drupal\farm_map_custom_layers\CustomMapLayerInterface $entity
 */
class CustomMapLayerForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->isNew() ? '' : $this->entity->getTitle(),
      '#description' => $this->t('Title of the custom map layer. It will be used in the layer selection ui.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\farm_map_custom_layers\Entity\CustomMapLayer::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->entity->isNew() ? TRUE : $this->entity->status(),
      '#description' => $this->t('Only enabled layers will be available on the map.'),
    ];

    $form['is_base_layer'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Is base layer'),
      '#default_value' => $this->isBaseLayer($form_state),
      '#description' => $this->t('Determines if the layer is a base layer or an overlay. Overlays are drawn on top of base layers.'),
      '#ajax' => [
        'callback' => '::updateGroup',
      ],
    ];

    $form['group'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Group'),
      '#description' => $this->t('The group of the layer. Layers with the same group will be grouped together in the layer selection ui. Can be left empty.'),
      '#states' => [
        'disabled' => [
          ':input[name="is_base_layer"]' => ['checked' => TRUE],
        ],
      ],
      '#default_value' => $this->isBaseLayer($form_state) ? 'Base layers' : $this->entity->getGroup(),
    ];

    $form['opacity'] = [
      '#type' => 'number',
      '#title' => $this->t('Opacity'),
      '#description' => $this->t('Opacity of the layer. 0 is fully transparent, 1 is fully opaque.'),
      '#default_value' => $this->entity->getOpacity() ?? 1,
      '#min' => 0,
      '#max' => 1,
      '#step' => 0.1,
    ];

    $form['source'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Source'),
    ];

    $form['source']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#description' => $this->t('In the future more layer types will be supported. For now only basic XYZ tile layers are.'),
      '#required' => TRUE,
      '#default_value' => $this->entity->get('type') ?? 'xyz',
      '#disabled' => TRUE,
      '#options' => [
        'xyz' => $this->t('XYZ'),
        // 'wms' => $this->t('WMS'),
        // 'geojson' => $this->t('GeoJSON'),
        // 'wkt' => $this->t('WKT'),
        // 'arcgis' => $this->t('ArcGIS'),
        // 'vector' => $this->t('Vector'),
        // 'cluster' => $this->t('Cluster'),
      ],
    ];

    $form['source']['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Url'),
      '#required' => TRUE,
      '#description' => $this->t('The url of the layer. For XYZ layers {x}, {y}, and {z} will be replaced with the tile coordinates.'),
      '#default_value' => $this->entity->get('url') ?? '',
    ];

    $form['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#open' => FALSE,
    ];

    $form['advanced']['options_override'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Layer options override'),
      '#description' => $this->t('Overrides layer options automatically set based on fields above. This should be a JSON object. For available values refer to <a href="https://github.com/farmOS/farmOS-map">farmOS-map documentation</a>.'),
      '#description_display' => 'before',
      '#default_value' => $this->entity->getOptionsOverride() ?? '',
    ];

    return $form;
  }

  /**
   * Updates layer group based on the is_base_layer value.
   *
   * For base layers group is always set to 'Base layers'.
   */
  public function updateGroup(array $form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $groupValue = '';
    if ($this->isBaseLayer($form_state)) {
      $groupValue = (string) $this->t('Base layers');
    }
    $response->addCommand(new InvokeCommand('#edit-group', 'val', [$groupValue]));
    return $response;
  }

  /**
   * Based on the current form state determine if the layer is a base layer.
   */
  protected function isBaseLayer(FormStateInterface $form_state): bool {
    $isBaseLayer = $this->entity->isBaseLayer();
    if ($form_state->hasValue('is_base_layer')) {
      $isBaseLayer = (bool) $form_state->getValue('is_base_layer');
    }
    return $isBaseLayer;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (
      !(bool) $form_state->getValue('is_base_layer')
      && $form_state->getValue('group') === (string) $this->t('Base layers')
    ) {
      $form_state->setErrorByName(
        'group',
        $this->t(
          '"@baseLayersGroup" group can\'t be used for overlay layers. It is reserved for base layers.', ['@baseLayersGroup' => (string) $this->t('Base layers')]
        )
      );
    }
    if ($form_state->getValue('options_override')) {
      try {
        $json = Json::decode($form_state->getValue('options_override'));
      }
      catch (\Exception $e) {
        $form_state->setErrorByName(
          'options_override',
          $this->t('Invalid JSON in layer options override.')
        );
      }
      if (!is_array($json)) {
        $form_state->setErrorByName(
          'options_override',
          $this->t('Layer options override must be a JSON object.')
        );
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $this->entity->setTitle($form_state->getValue('label'));
    $this->entity->setStatus($form_state->getValue('status'));
    $this->entity->setType($form_state->getValue('type'));
    $this->entity->setUrl($form_state->getValue('url'));
    $this->entity->setBaseLayer((bool) $form_state->getValue('is_base_layer'));
    $this->entity->setGroup($form_state->getValue('group'));
    $this->entity->setOpacity(floatval($form_state->getValue('opacity')));
    $this->entity->setOptionsOverride($form_state->getValue('options_override'));
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new custom map layer %label.', $message_args)
      : $this->t('Updated custom map layer %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
