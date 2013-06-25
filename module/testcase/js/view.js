$(document).ready(function() 
{
    $(".runCase").colorbox({width:900, height:550, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});
    $(".results").colorbox({width:900, height:550, iframe:true, transition:'none'});
})
