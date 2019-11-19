<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Showing/Hiding Overlays</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 80%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    .customMarker {
        position:absolute;
        cursor:pointer;
        background:#424242;
        width:40px;
        height:40px;
        /* -width/2 */
        margin-left:-50px;
        /* -height + arrow */
        margin-top:-110px;
        border-radius:5px;
        padding:0px;
    }
    .customMarker:after {
        content:"";
        position: absolute;
        bottom: -10px;
        left: 10px;
        border-width: 10px 10px 0;
        border-style: solid;
        border-color: #424242 transparent;
        display: block;
        width: 0;
    }
    .customMarker img {
        width:36px;
        height:35px;
        margin:2px;
        border-radius:5px;
    }

</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_KEY') }}"></script>
<script>
      // This example adds hide() and show() methods to a custom overlay's prototype.
      // These methods toggle the visibility of the container <div>.
      // Additionally, we add a toggleDOM() method, which attaches or detaches the
      // overlay to or from the map.

      var overlay;

      CustomMarker.prototype = new google.maps.OverlayView();

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: {lat: 62.323907, lng: -150.109291},
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

        var latlng = new google.maps.LatLng(62.323907, -150.109291);

        // The photograph is courtesy of the U.S. Geological Survey.
        var srcImage = 'https://fooder-dev-s3.s3-ap-northeast-1.amazonaws.com/';
        srcImage += 'images/1573100726menu.jpg';

        overlay = new CustomMarker(latlng, srcImage, map);
    }

    /** @constructor */
    function CustomMarker(latlng, image, map) {

        // Now initialize all properties.
        this.latlng_ = latlng;
        this.image_ = image;
        this.map_ = map;

        // Define a property to hold the image's div. We'll
        // actually create this div upon receipt of the onAdd()
        // method so we'll leave it null for now.
        this.div_ = null;

        // Explicitly call setMap on this overlay
        this.setMap(map);
    }

      /**
       * onAdd is called when the map's panes are ready and the overlay has been
       * added to the map.
       */
       CustomMarker.prototype.onAdd = function() {

        var div = document.createElement('div');

        // Create the DIV representing our CustomMarker
        div.className = "customMarker"

        // Create the img element and attach it to the div.
        var img = document.createElement('img');
        img.src = this.image_;

        div.appendChild(img);

        this.div_ = div;

        // Add the element to the "overlayImage" pane.
        var panes = this.getPanes();
        panes.overlayImage.appendChild(this.div_);
    };

    CustomMarker.prototype.draw = function() {

        // Resize the image's div to fit the indicated dimensions.
        var div = this.div_;

        // Position the overlay 
        var point = this.getProjection().fromLatLngToDivPixel(this.latlng_);
        if (point) {
            div.style.left = point.x + 'px';
            div.style.top = point.y + 'px';
        }
    };

    CustomMarker.prototype.onRemove = function() {
        this.div_.parentNode.removeChild(this.div_);
    };

    CustomMarker.prototype.getPosition = function () {
        return this.latlng_;
    };

      google.maps.event.addDomListener(window, 'load', initMap);
  </script>
</head>
<body>
  <div id="map"></div>
</body>
</html>