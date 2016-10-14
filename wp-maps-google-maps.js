    var xmlObject = jQuery.parseXML(<?php echo $object ?>);

    function geocodeIdToString( latlng) {
        var geoCoder = new google.maps.Geocoder;
        geoCoder.geocode({'location': latlng}, function(results, status) {
            var name = results[1];
        }
    }

