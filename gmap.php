<!--Script Google Map Init-->

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&callback=initializeMaps&callback=initializeMaps"></script>

<script type="text/javascript">
var markers = [
    <?php echo $markers; ?>
];

function initializeMaps() {
    var latlon = new google.maps.LatLng(<?php echo $first_pin_lat . "," . $first_pin_lon; ?>);
    var myOptions = {
        zoom: <?php echo $zoom; ?>,
        center: latlon,
        navigationControl: false,
        scaleControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        scrollwheel: false,
        panControl: true
    };

    var map = new google.maps.Map(document.getElementById("map<?php echo $map_id; ?>"),myOptions);
    var infowindow = new google.maps.InfoWindow(), marker, i;
    for (i = 0; i < markers.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(markers[i][1], markers[i][2]),
            map: map,
            icon: '' // null = default icon
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(markers[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
}
</script>

<div id="map<?php echo $map_id; ?>" style="width:<?php echo $map_width; ?>;height:<?php echo $map_height; ?>;"></div>
