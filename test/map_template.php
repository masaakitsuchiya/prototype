
    <div id="map"></div>
    <script>
    var plat;
    var plon;

    var address = encodeURIComponent("東京都杉並区高円寺南6－14");
    var google_geolocation_api = "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyC2ZQj8dGnc9KjzFs1peNKU8JIRHvTQofM";

    $.getJSON(google_geolocation_api, function(json){
      console.dir(json);
      plat = json.results["0"].geometry.location.lat;
      plon = json.results["0"].geometry.location.lng;
      console.log(plat);
      console.log(plon);
    });

      // The following example creates a marker in Stockholm, Sweden using a DROP
      // animation. Clicking on the marker will toggle the animation between a BOUNCE
      // animation and no animation.

      var marker;

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: plat, lng: plon}
        });

        marker = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: {lat: plat, lng: plon}
        });
        marker.addListener('click', toggleBounce);
      }

      function toggleBounce() {
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2ZQj8dGnc9KjzFs1peNKU8JIRHvTQofM&callback=initMap">
    </script>