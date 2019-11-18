<!DOCTYPE html>
<html>
<head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>
</head>
<body>
    <!-- Add an input button to initiate the toggle method on the overlay. -->
    <div id="floating-panel">
        <input type="button" value="Toggle visibility" onclick="overlay.toggle();"></input>
        <input type="button" value="Toggle DOM attachment" onclick="overlay.toggleDOM();"></input>
    </div>
    <div id="map"></div>
    <script>
        // This example creates a custom overlay called USGSOverlay, containing
        // a U.S. Geological Survey (USGS) image of the relevant area on the map.

        // Set the custom overlay object's prototype to a new instance
        // of OverlayView. In effect, this will subclass the overlay class therefore
        // it's simpler to load the API synchronously, using
        // google.maps.event.addDomListener().
        // Note that we set the prototype to an instance, rather than the
        // parent class itself, because we do not wish to modify the parent class.

        var overlay;
        // USGSOverlay.prototype = new google.maps.OverlayView();

        // Initialize the map and the custom overlay.

        function initMap() {
            USGSOverlay.prototype = new google.maps.OverlayView();
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 11,
                center: {lat: 62.323907, lng: -150.109291},
                mapTypeId: 'satellite'
            });

            var bounds = new google.maps.LatLngBounds(
              new google.maps.LatLng(62.281819, -150.287132),
              new google.maps.LatLng(62.400471, -150.005608));

            // The photograph is courtesy of the U.S. Geological Survey.
            var srcImage = 'https://developers.google.com/maps/documentation/' +
            'javascript/examples/full/images/talkeetna.png';

            // The custom USGSOverlay object contains the USGS image,
            // the bounds of the image, and a reference to the map.
            overlay = new USGSOverlay(bounds, srcImage, map);
        }

        /** @constructor */
        function USGSOverlay(bounds, image, map) {

            // Initialize all properties.
            this.bounds_ = bounds;
            this.image_ = image;
            this.map_ = map;

            // Define a property to hold the image's div. We'll
            // actually create this div upon receipt of the onAdd()
            // method so we'll leave it null for now.
            this.div_ = null;

            // Explicitly call setMap on this overlay.
            this.setMap(map);
        }

        /**
        * onAdd is called when the map's panes are ready and the overlay has been
        * added to the map.
        */
        USGSOverlay.prototype.onAdd = function() {

            var div = document.createElement('div');
            div.style.borderStyle = 'none';
            div.style.borderWidth = '0px';
            div.style.position = 'absolute';

            // Create the img element and attach it to the div.
            var img = document.createElement('img');
            img.src = this.image_;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.position = 'absolute';
            div.appendChild(img);

            this.div_ = div;

            // Add the element to the "overlayLayer" pane.
            var panes = this.getPanes();
            panes.overlayLayer.appendChild(div);
        };

        USGSOverlay.prototype.draw = function() {

            // We use the south-west and north-east
            // coordinates of the overlay to peg it to the correct position and size.
            // To do this, we need to retrieve the projection from the overlay.
            var overlayProjection = this.getProjection();

            // Retrieve the south-west and north-east coordinates of this overlay
            // in LatLngs and convert them to pixel coordinates.
            // We'll use these coordinates to resize the div.
            var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
            var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

            // Resize the image's div to fit the indicated dimensions.
            var div = this.div_;
            div.style.left = sw.x + 'px';
            div.style.top = ne.y + 'px';
            div.style.width = (ne.x - sw.x) + 'px';
            div.style.height = (sw.y - ne.y) + 'px';
        };

        // The onRemove() method will be called automatically from the API if
        // we ever set the overlay's map property to 'null'.
        USGSOverlay.prototype.onRemove = function() {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        };

        // Set the visibility to 'hidden' or 'visible'.
        USGSOverlay.prototype.hide = function() {
            if (this.div_) {
                // The visibility property must be a string enclosed in quotes.
                this.div_.style.visibility = 'hidden';
            }
        };

        USGSOverlay.prototype.show = function() {
          if (this.div_) {
            this.div_.style.visibility = 'visible';
        }
    };

    USGSOverlay.prototype.toggle = function() {
      if (this.div_) {
        if (this.div_.style.visibility === 'hidden') {
          this.show();
      } else {
          this.hide();
      }
  }
};

// Detach the map from the DOM via toggleDOM().
// Note that if we later reattach the map, it will be visible again,
// because the containing <div> is recreated in the overlay's onAdd() method.
USGSOverlay.prototype.toggleDOM = function() {
  if (this.getMap()) {
    // Note: setMap(null) calls OverlayView.onRemove()
    this.setMap(null);
} else {
    this.setMap(this.map_);
}
};
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHpPBGr4o-HSytpq2OdhtYKGjH4JLh-bA&callback=initMap"
async defer></script>
</body>
</html>