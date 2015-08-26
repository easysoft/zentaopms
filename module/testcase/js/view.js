$(document).ready(function() 
{
    if(onlybody != 'yes')$(".runCase").modalTrigger({width:900, type:'iframe', afterHide:function(){parent.location.href=parent.location.href;}});
    if(onlybody != 'yes')$(".results").modalTrigger({width:900, type:'iframe'});
})
