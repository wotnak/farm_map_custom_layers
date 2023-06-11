(function (farmOS, drupalSettings) {
  farmOS.map.behaviors.custom_map_layers = {
    attach: function (instance) {
      if (!drupalSettings.farm_map.behaviors.custom_map_layers) {
        return;
      }
      Object.values(drupalSettings.farm_map.behaviors.custom_map_layers).forEach(function (layer) {
        const options = {
          title: layer.title,
          url: layer.url,
          base: layer.isBaseLayer,
        };
        if (layer.group) {
          options.group = layer.group;
        }
        instance.addLayer(layer.type, options);
      })
    },
  };
}(farmOS, drupalSettings));
