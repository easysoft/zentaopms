$(function() 
{ 
    if(typeof(listName) != 'undefined') setModal4List('iframe', listName, function(){$(".colorbox").colorbox({width:960, height:550, iframe:true, transition:'none'});});
});
