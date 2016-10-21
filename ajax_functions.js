
function getMarkers(map) {
    jQuery.post(my_ajax_obj.ajax_url, {
        _ajax_nonce: my_ajax_obj.nonce,
        action: "get_markers" },
        function(data) {
                jQuery.each(data.data, function(index) {
                    var id = data.data[index]['id'];

                    var title = data.data[index]['title'];
                    var description = data.data[index]['description'];
                    var position = data.data[index]['position'];
                    // add fittext property in order to remove the display error
                    var point = position.slice(1,-1).split(",");
                    var latlng = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
                    //this Function add a new marker in the current map and fill each field
                    var marker = new google.maps.Marker({
                        map: map,
                        position: latlng,
                        title: title
                    });
                    var content = jQuery('<div> <div style="color: #7c795d;font-family: \'Trocchi\';font-size: 12px;font-weight: normal; line-height: 10px; margin: 0;">'+title +'</div><br><div style="color: #4c4a37; font-family:\'Source Sans Pro\', sans-serif; font-size: 10px; line-height: 10px; margin: 0 0 5px;">'+description+'<br> </div> </div>');

                    content.append(jQuery('<button style="padding: 5px 10px;font-size: 5px;" id='+id+' onclick="deleteStage('+id+')" type="button" > Supprimer </button>'));
                    var infowindow = new google.maps.InfoWindow({
                        content: content.html()
                    });
                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                        //Center map on the stage
                        map.panTo(latlng);
                        map.setZoom(4);
                    });
                  });
    }
    ).fail(function() {
        //Display error message
    });
}

function saveMarker(title, description, position) {
    jQuery.post(my_ajax_obj.ajax_url, {         //POST request
        _ajax_nonce: my_ajax_obj.nonce,     //nonce
        action: "add_marker",            //action
        title: title,
        description: description,
        position: position
       },
        function(data, status) {
            //Stage save add something
            location.reload();
        }).fail(function(){
            alert("Your marker hasn't been saved.")
        });
}

function deleteStage(id){
    jQuery.post(my_ajax_obj.ajax_url, {         //POST request
        _ajax_nonce: my_ajax_obj.nonce,     //nonce
        action: "delete_marker",            //action
        id: id
        }, function(data, status) {
          location.reload();
        }).fail(function() {
            alert("You cannot remove a marker which doesn't belong to you !");
        });
}
