jQuery(document).ready(function($) {
    map = new google.maps.Map(document.getElementById('map'), {
       center: {lat: -34.397, lng: 150.644},
       mapTypeId: google.maps.MapTypeId.TERRAIN,
       scrollwheel: true,
       zoom: 1
   });
   var controlDiv = document.createElement('div');
   var controlUI = document.createElement('div');
   var controlText = document.createElement('div');
   var contentString = '<div id="marker_content"> Titre <input id="title_content" type="text" name="Title"> <br> Contact <input id="contact_content" type="text" name="contact"> <br> Description <textarea id="description_content" cols="40" rows="5" type="text" name="description"> </textarea>';
   // Set CSS for the control border.
   controlUI.style.backgroundColor = '#fff';
   controlUI.style.border = '2px solid #fff';
   controlUI.style.borderRadius = '3px';
   controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
   controlUI.style.cursor = 'pointer';
   controlUI.style.marginBottom = '22px';
   controlUI.style.textAlign = 'Ajouter';
   controlUI.title = 'Ajoute un stage';
   controlDiv.appendChild(controlUI);

   //Set CSS for the control interior.
   controlText.style.color = 'rgb(25,25,25)';
   controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
   controlText.style.fontSize = '16px';
   controlText.style.lineHeight = '38px';
   controlText.style.paddingLeft = '5px';
   controlText.style.paddingRight = '5px';
   controlText.innerHTML = 'Ajouter';
   controlUI.appendChild(controlText);

    controlDiv.index = 1;
    var loopControl = false;
    var marker = new google.maps.Marker();
    controlUI.addEventListener('click', function () {
        loopControl = !loopControl;
        if (loopControl) {
        controlText.innerHTML = "Place ton marqueur";
        var infoWindow = new google.maps.InfoWindow();
        infoWindow.setContent(contentString);
        google.maps.event.addListener(map, 'click', function(event) {
            // setting marker
            marker.setMap(map);
            marker.setPosition(event.latLng);
            infoWindow.open(map, marker);
            controlText.innerHTML = "Enregistrer";

            //creation of listener to display information about new marker
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        });
        }
        else {
            var title = jQuery('#title_content').val();
            var contact = jQuery('#contact_content').val();
            var content = jQuery('#description_content').val();
            saveMarker(title, contact+" "+content, marker.getPosition().toString());
            google.maps.event.clearListeners(map, 'click');
       }
   });

   map.controls[google.maps.ControlPosition.LEFT_CENTER].push(controlDiv);
   //Add marker on the map with call of plugin API

   getMarkers(map);
});

// google's api needs of this function to load
function initMap() {}

