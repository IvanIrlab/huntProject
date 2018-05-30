'use strict'

$(function()
{
  // Launch MAP utilities
  var map = new HuntMap();
  map.init();
  map.mapAction();

  // Launch TRADUCTOR utilities
  var trad = new Trad();
  trad.listener();
});
