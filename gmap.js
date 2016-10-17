//* Google map *//
var markers = [
    ['EEMI', 48.8688356, 2.341242599999987]
];

function initializeMaps() {
    var latlng = new google.maps.LatLng(48.8688356, 2.341242599999987);
    var myOptions = {
        zoom: 16,
        center: latlng,
        navigationControl: false,
        scaleControl: false,
        draggable: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        scrollwheel: false
    };

    var map = new google.maps.Map(document.getElementById("map"),myOptions);
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

$(window).load(function(){
    initializeMaps()
});