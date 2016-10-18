<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvVIeQvhtbNRKZt-mM1HNyTF7rV-EGV8Y&callback=initializeMaps&callback=initializeMaps"></script>

<script type="text/javascript">

function initializeMaps() {
  var map = new google.maps.Map(document.getElementById('map<?php echo $map_id; ?>'), {
    zoom: <?php echo $zoom; ?>
  });

  	var starting_pin = new google.maps.LatLng(<?php echo $starting_pin; ?>);
	var ending_pin = new google.maps.LatLng(<?php echo $ending_pin; ?>);


	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer({
    draggable: true,
    map: map,
    panel: document.getElementById('right-panel')
  });

  directionsDisplay.addListener('directions_changed', function() {
    computeTotalDistance(directionsDisplay.getDirections());
  });

  displayRoute( starting_pin, ending_pin, directionsService,
      directionsDisplay);
}

function displayRoute(origin, destination, service, display) {
  service.route({
    origin: origin,
    destination: destination,
    travelMode: google.maps.TravelMode.DRIVING,
    avoidTolls: true
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      display.setDirections(response);
    } else {
      alert('Could not display directions due to: ' + status);
    }
  });
}

function computeTotalDistance(result) {
  var total = 0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  total = total / 1000;
  document.getElementById('total').innerHTML = total + ' km';
}
</script>

<div id="map<?php echo $map_id; ?>" style="width:<?php echo $map_width; ?>;height:<?php echo $map_height; ?>;"></div>