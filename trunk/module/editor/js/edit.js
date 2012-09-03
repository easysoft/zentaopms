$(function()
{
  var windowHeight = $(window).height();
  var showHeight   = Math.ceil(windowHeight *0.3);
  if($('#showContent').attr('id') != undefined)
  {
    var fileHeight = windowHeight  - showHeight - 150;
    $('#showContent').height(showHeight);
    $('#fileContent').height(fileHeight);
  }
  else
  {
    var fileHeight = windowHeight - 140;
    $('#fileContent').height(fileHeight);
  }
})
