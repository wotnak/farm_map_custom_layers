(function (farmOS, drupalSettings) {
  farmOS.map.behaviors.custom_map_layers = {
    attach: function (instance) {
      if (!drupalSettings.farm_map.behaviors.custom_map_layers) {
        return;
      }
      Object.values(drupalSettings.farm_map.behaviors.custom_map_layers).forEach(function (layerConfig) {

        // Prepare options.
        let options = {
          title: layerConfig.title,
          url: layerConfig.url,
          base: layerConfig.isBaseLayer,
        };
        if (layerConfig.group) {
          options.group = layerConfig.group;
        }

        // Apply options override.
        if (layerConfig.optionsOverride) {
          const optionsOverride = JSON.parse(layerConfig.optionsOverride);
          if (
            typeof optionsOverride === 'object'
            && optionsOverride !== null
            && !Array.isArray(optionsOverride)
            && Object.keys(optionsOverride).length > 0
          ) {
            options = Object.assign(options, optionsOverride);
          }
        }

        // Create layer.
        const layer = instance.addLayer(layerConfig.type, options);

        // Configure layer opacity.
        if (layerConfig.opacity) {
          layer.setOpacity(layerConfig.opacity);
        }

      })
    },
  };
}(farmOS, drupalSettings));
