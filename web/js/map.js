'use strict'

/*======== Constructor ========*/
var HuntMap = function(){
  this.map = { map : null};
  this.marker = { marker : null};
  this.markerPlaces = [];
  this.markerPlacesId = 0;
  this.searchBox = {searchBox : null};
  this.lat = null;
  this.lng = null;
  this.cities = [{city : " ",
                 lat : -21.133111,
                 long : 55.539576},
                {city : "Saint-Denis",
                 lat : -20.888122,
                 long : 55.455515},
                 {city : "La Possession",
                  lat : -20.927539,
                  long : 55.368173},
                 {city : "Le Port",
                  lat : -20.939184,
                  long : 55.295144},
                 {city : "Saint-Paul",
                 lat : -21.039750,
                 long : 55.289552},
                 {city : "Saint-Leu",
                  lat : -21.171692,
                  long : 55.288296},
                  {city : "Saint-Louis",
                   lat : -21.290508,
                   long : 55.409496},
                  {city : "Saint-Pierre",
                   lat : -21.307837,
                   long : 55.480327},
                 {city : "Saint-Joseph",
                  lat : -21.314457,
                  long : 55.642917},
                  {city : "Saint-Philippe",
                   lat : -21.356878,
                   long : 55.764656},
                   {city : "Sainte-Rose",
                    lat : -21.131185,
                    long : 55.791251},
                   {city : "Saint-Benoît",
                    lat : -21.042895,
                    long : 55.717771},
                   {city : "Saint-André",
                   lat : -20.957161,
                   long : 55.651384},
                   {city : "Saint-Suzanne",
                    lat : -20.925995,
                    long : 55.596603},
                    {city : "Saint-Cilaos",
                     lat : -21.135135,
                     long : 55.462962},
                    {city : "Plaine des Palmistes",
                     lat : -21.140596,
                     long : 55.632812},
                   {city : "Le Tampon",
                    lat : -21.242470,
                    long : 55.555654}
               ];
};

/*======== Methods ========*/
HuntMap.prototype.init = function() {
  // Position by default (LA REUNION)
  var centerpos = new google.maps.LatLng(-21.133111, 55.539576);

  // Relative options Map
  var optionsGmaps = {
      center:centerpos,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoom: 10
  };
  // ROADMAP can be replace by SATELLITE, HYBRID or TERRAIN
  // Zoom : 0 = hole earth, 19 = near the street

  // Initialisation of map for each Id map element"
  this.map = new google.maps.Map(document.getElementById("map"), optionsGmaps);

  /*this.marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title: 'Click to zoom'
        });*/

  /*this.map.addListener('click', function(e) {
    // 3 seconds after the center of the map has changed, pan back to the
    // marker.
    console.log(event);

    this.placeMarkerAndPanTo(e.latLng, this.map).bind(this);
    /*window.setTimeout(function() {
      map.panTo(marker.getPosition());
    }, 3000);*/
    //this.map.panTo(marker.getPosition());

    //console.log(this.getMyPosition);
    //this.map.setZoom(8);
    //this.map.setCenter(marker.getPosition());
  //});

  this.getMyPosition();

}

HuntMap.prototype.placeMarkerAndPanTo = function(latLng, map) {
  console.log("test ok");
  var marker = new google.maps.Marker({
    position: latLng,
    map: map
  });
  this.map.panTo(latLng);
}

HuntMap.prototype.selectPlace = function() {
  $('#map').on('click',this.placeMarker.bind(this));
}

HuntMap.prototype.placeMarker = function() {
  console.log('click');
  var marker = new google.maps.Marker({
      position: location,
      map: map
  });
}

HuntMap.prototype.mapAction = function() {
  $('#start-city').on('change',this.locateCity.bind(this));
  this.map.addListener('click',this.placeCoordOnClick.bind(this));
  this.searchBoxInit();

console.log(this.map);
  var mapTemp = this.map;
  var timeoutID = window.setTimeout(function(){
  for(var i = 1 ; i < $('#spot-table tr').length ; i++ ){
      console.log('lat : ');
  console.log($('#spot-table tr:nth-child('+i+') td:nth-child(3)').html());
              console.log('long : ');
                  this.addMarker($('#spot-table tr:nth-child('+i+') td:nth-child(3)').html(),$('#spot-table tr:nth-child('+i+') td:nth-child(4)').html());
    console.log($('#spot-table tr:nth-child('+i+') td:nth-child(4)').html());
    this.addHuntPlaces($('#spot-table tr:nth-child('+i+') td:nth-child(3)').html(),$('#spot-table tr:nth-child('+i+') td:nth-child(4)').html(), mapTemp)
  }}, 3000);


}

HuntMap.prototype.searchBoxInit = function() {

  var address = document.getElementById('map-address');

  this.searchBox = new google.maps.places.SearchBox(address);
  this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(address);

  // Bias the SearchBox results towards current map's viewport.
  var searchBox = this.searchBox;
  google.maps.event.addListener(this.map,'bounds_changed', function() {
    //searchBox.setBounds(this.map.getBounds());
    searchBox.setBounds(this.getBounds());
  });

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.

  this.searchBox.addListener('places_changed', this.placeChanged.bind(this));
}

HuntMap.prototype.placeChanged = function() {

  var places = this.searchBox.getPlaces();

  if (places.length == 0) {
    return;
  }
  var markers = [];
  // Clear out the old markers.
  markers.forEach(function(marker) {
  marker.setMap(null);
  })

  markers = [];
  // For each place, get the icon, name and location.
  var bounds = new google.maps.LatLngBounds();
  console.log(places);
  console.log(places[0].geometry.location.lat());
  console.log(places[0].geometry.location.lng());

  this.lat = places[0].geometry.location.lat();
  this.lng = places[0].geometry.location.lng();

  var searchPos = new google.maps.LatLng(this.lat,this.lng);
  var zoom = 13;

  // Options Map
  var optionsGmaps = {
      center:searchPos,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoom: zoom
  };
  console.log(optionsGmaps);
  console.log(this.lat);
  console.log(this.lng);
  //this.map.setCenter({lat : this.lat,lng : this.lng});
  this.map.setOptions(optionsGmaps);
  this.addMarker(this.lat,this.lng);

  //Update placeholder in file.twig
  //$('#place-coord-lat').val(this.lat);
  //$('#place-coord-long').val(this.lng);
  $('#place-address').val($('#map-address').val());

  //Add listeners on marker recently created
  this.marker.addListener('click', this.infoMarker.bind(this));
  this.marker.addListener('dblclick', this.removeMarker.bind(this));

}

HuntMap.prototype.placeCoordOnClick = function(e) {
  //var coordLat = $('#coord-lat');
  //coordLat.text(e.latLng.lat());
  console.log('test');
  $('#place-coord-lat').val(e.latLng.lat());

  //var coordLng = $('#coord-long');
  //coordLng.text(e.latLng.lng());
  $('#place-coord-long').val(e.latLng.lng());
  $('#place-address').val('');

  this.addMarker(e.latLng.lat(),e.latLng.lng());

  this.marker.addListener('click', this.infoMarker.bind(this));
  this.marker.addListener('dblclick', this.removeMarker.bind(this));
}

HuntMap.prototype.placeCoord = function(coord) {
  //var coordLat = $('#coord-lat');
  //coordLat.text(coord.lat);
  $('#place-coord-lat').val(coord.lat);

  //var coordLng = $('#coord-long');
  //coordLng.text(coord.Lng);
  $('#place-coord-long').val(coord.Lng);

  $('#place-address').val(coord.city);

  this.addMarker(coord.lat,coord.Lng);

  //Add listeners on marker recently created
  this.marker.addListener('click', this.infoMarker.bind(this));
  this.marker.addListener('dblclick', this.removeMarker.bind(this));
}


 function addHuntPlaces(lat, lng, map) {
  // New LatLng Object for Google Maps with position parameters
  var latlng = new google.maps.LatLng(lat, lng);
console.log(map);
  this.marker = new google.maps.Marker({
      position: latlng,
      map: map//,
      //icon: pinImage,
      //shadow: pinShadow
  });
  console.log('addHuntPlaces -> lat:'+lat+' lng:'+lng);
  var pinColor = "6b6969";
  var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
  new google.maps.Size(21, 34),
  new google.maps.Point(0,0),
  new google.maps.Point(10, 34));
  var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
  new google.maps.Size(40, 37),
  new google.maps.Point(0, 0),
  new google.maps.Point(12, 35));



}

HuntMap.prototype.addMarker = function(lat, lng) {
  // New LatLng Object for Google Maps with position parameters
  var latlng = new google.maps.LatLng(lat, lng);

  this.marker = new google.maps.Marker({
      position: latlng,
      map: this.map,
      Id: this.markerPlacesId++,
      draggable:true
  });

  this.markerPlaces.push(this.marker);
}

HuntMap.prototype.infoMarker = function(e) {
  //Debug latLng of Map
  /*var coordLat = $('#coord-lat');
  coordLat.text(e.latLng.lat());
  var coordLng = $('#coord-long');
  coordLng.text(e.latLng.lng());*/
}

HuntMap.prototype.removeMarker = function(e) {
  for(var i=0; i < this.markerPlaces.length; i++){
    if(e.latLng.lat() == this.markerPlaces[i].position.lat() && e.latLng.lng() == this.markerPlaces[i].position.lng()){
      console.log(this.markerPlaces[i].position.lat());
      console.log(this.markerPlaces[i].position.lng());
      this.markerPlaces[i].setMap(null);
      this.markerPlaces.splice(i,1);
    }
  }
}

HuntMap.prototype.locateCity = function() {
  this.cityMap($('#start-city').val());
}

HuntMap.prototype.cityMap = function(cityIndex) {
  var citypos = new google.maps.LatLng(this.cities[cityIndex].lat, this.cities[cityIndex].long);
  var zoom = 13;

  if(cityIndex == 0){
    zoom = 10;
  }
  // Options Map
  var optionsGmaps = {
      center:citypos,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoom: zoom
  };

  this.map.setOptions(optionsGmaps);
  this.placeCoord({city: this.cities[cityIndex].city,lat: this.cities[cityIndex].lat, Lng: this.cities[cityIndex].long});

}

HuntMap.prototype.getMyPosition = function() {
  if(navigator.geolocation){
    var options = { enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0 };
    navigator.geolocation.getCurrentPosition(this.myPosition, this.positionError, options);
  }
  else{
    alert("Geolocation APIs not supported by your navigator!");
  }
}

HuntMap.prototype.myPosition = function(position) {
  var infopos = "";
  var latlng;
  infopos += " Latitude : "+position.coords.latitude +"\n";
  infopos += "Longitude: "+position.coords.longitude+"\n";
  infopos += "Altitude : "+position.coords.altitude +"\n";
  document.getElementById("pos").innerHTML = infopos;

  // New LatLng Object for Google Maps with position parameters
  latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

  // Add a marker for the position found
  var marker = new google.maps.Marker({ position: latlng,
                                        map: map.map,
                                        title:"Vous êtes ici",
                                        //icon : "img/location-marker.png"
                                      });
}

HuntMap.prototype.positionError = function(error) {
  var info = "Erreur lors de la géolocalisation : ";
  switch(error.code) {
    case error.TIMEOUT:
  	 info += "Timeout !";
     break;
    case error.PERMISSION_DENIED:
      info += "Permission Denied!";
      break;
    case error.POSITION_UNAVAILABLE:
    	info += "Position not found!";
      break;
    case error.UNKNOWN_ERROR:
    	info += "Unknown Error";
      break;
  }
}
