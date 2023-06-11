(function (farmOS, drupalSettings) {
  farmOS.map.behaviors.custom_map_layers = {
    attach: function (instance) {
      if (!drupalSettings.farm_map.behaviors.custom_map_layers) {
        return;
      }
      Object.values(drupalSettings.farm_map.behaviors.custom_map_layers).forEach(function (layerConfig) {
        const options = {
          title: layerConfig.title,
          url: layerConfig.url,
          base: layerConfig.isBaseLayer,
        };
        if (layerConfig.group) {
          options.group = layerConfig.group;
        }
        const layer = instance.addLayer(layerConfig.type, options);
        if (layerConfig.opacity) {
          layer.setOpacity(layerConfig.opacity);
        }
      })
    },
  };
}(farmOS, drupalSettings));
