
jQuery(document).ready(function($) {



	var map = new google.maps.Map(document.getElementById('map'), {
   		center: {lat: -34.397, lng: 150.644},
   		mapTypeId: google.maps.MapTypeId.TERRAIN,
   		scrollwheel: true,
   		zoom: 1
 	});
    //Load geocoder
  	var geocoder = new google.maps.Geocoder;
    // add all animation which we want in the table
  	$(".row").each(function(){

        // add fittext property in order to remove the display error
        $(this).children("td").fitText(1.5);
      var description = $(this).children("td.description").html();
      var title = $(this).children("td.title").html();
    	var position = $(this).children("td").children("div.coordinates").html();
  		var point = position.slice(1,-1).split(",");

  		var latlng = new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1]));
      var placement = $(this).children("td").children("div.reverse_geocode");
          geocoder.geocode({'location': latlng}, function(results, status){
          if (status === google.maps.GeocoderStatus.OK) {
            placement.html(results[1].formatted_address);
          }
          else {
            placement.html("I don't know");
          }
      });
  		$(this).mouseleave(function(){
  			//set normal backgroud color
  			$(this).css("background-color", "white");
  		});
  		$(this).mouseover(function(){
  			//Set .row background color
  			$(this).css("background-color", "grey");
  		});

      //Function to put a new marker in the current maps
      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        title: title
      });
      var infowindow = new google.maps.InfoWindow({
        content: description
      });
      marker.addListener('click', function() {
        infowindow.open(map, marker);
      })
      $(this).click(function(){
        //Center map on the stage
        map.panTo(latlng);
        map.setZoom(4);
        infowindow.open(map, marker);
      });
  		//Find the reverse geolocalisation
	   });
});
//just for the google api
function initMap() {}
