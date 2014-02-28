/*An example of using the MQA.EventUtil to hook into the window load event and execute defined function 
passed in as the last parameter. You could alternatively create a plain function here and have it 
executed whenever you like (e.g. <body onload="yourfunction">).*/ 

MQA.EventUtil.observe(window, 'load', function() {

/*Create an object for options*/ 
var options={
elt:document.getElementById('mapquestthat_map'),       /*ID of element on the page where you want the map added*/ 
zoom:10,                                  /*initial zoom level of the map*/ 
latLng:{lat:39.743943, lng:-105.020089},  /*center of map in latitude/longitude */ 
mtype:'map',                              /*map type (map)*/ 
bestFitMargin:0,                          /*margin offset from the map viewport when applying a bestfit on shapes*/ 
zoomOnDoubleClick:true                    /*zoom in when double-clicking on map*/ 
};

/*Construct an instance of MQA.TileMap with the options object*/ 
window.map = new MQA.TileMap(options);

var custom=new MQA.Poi( {lat:39.743943, lng:-105.020089} );
var icon=new MQA.Icon("http://developer.mapquest.com/content/documentation/common/images/smiley.png",32,32);
custom.setIcon(icon);
map.addShape(custom);

  /*Sets the rollover content of the POI.*/ 
  custom.setRolloverContent('Invesco Field');

  /*Sets the InfoWindow contents for the POI. By default, when the POI receives a mouseclick 
  event, the InfoWindow will be displayed with the HTML passed in to MQA.POI.setInfoContentHTML method.*/ 
  custom.setInfoContentHTML('Home of the Denver Broncos');
});