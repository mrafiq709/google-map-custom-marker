<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Showing/Hiding Overlays</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/marker.css')}}">
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
          zoom: 17,
          center: new google.maps.LatLng(37.77088429547992, -122.4135623872337),
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

        var latlng = new google.maps.LatLng(37.77085, -122.41356);

        // var srcImage = 'https://fooder-dev-s3.s3-ap-northeast-1.amazonaws.com/';
        // srcImage += 'images/1573100726menu.jpg';

        var srcImage = 'http://placekitten.com/90/90';

        overlay = new CustomMarker(latlng, srcImage, map);
    }

    /** @constructor */
    function CustomMarker(latlng, image, map) {

        // Now initialize all properties.
        this.latlng_ = latlng;
        this.image_ = image;
        // this.map_ = map;

        // Define a property to hold the image's div. We'll
        // actually create this div upon receipt of the onAdd()
        // method so we'll leave it null for now.
        // this.div_ = null;

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
        div.className = "customMarker";
        div.id = "customMarkerId";

        // Create the img element and attach it to the div.
        var img = document.createElement('img');
        img.src = this.image_;

        div.appendChild(img);
        google.maps.event.addDomListener(div, "click", function (event) {
            google.maps.event.trigger(clickMe(), "click");
        });

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
        if (this.div_){
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        }
    };

    CustomMarker.prototype.getPosition = function () {
        return this.latlng_;
    };

    var toggle = 0;

    function clickMe(){
        // var x = document.getElementsByClassName("customMarker")[0].className;
        // console.log('className: ' + x);

        if (!toggle) {
            // Create dynamic class for big marker
            document.getElementById("customMarkerId").classList.add('bigMarker');

            // Big Marker Styles
            var customBigMarker = document.getElementsByClassName("bigMarker")[0];
            customBigMarker.style.backgroundColor = '#257BC1';

        }else{
            // Remove Big Marker Class
            document.getElementById("customMarkerId").classList.remove('bigMarker');

            // Small Marker Styles
            var customSmallMarker = document.getElementsByClassName("customMarker")[0];
            customSmallMarker.style.backgroundColor = '#424242';
        }
        
        toggle++;
        toggle %= 2;
    }

    google.maps.event.addDomListener(window, 'load', initMap);
</script>
</head>
<body>
  <div id="map"></div>
</body>
</html>