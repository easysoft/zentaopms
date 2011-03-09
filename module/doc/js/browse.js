$(function(){$("#byQuery").css("color","red");}); 
/* Search doc. */
$("#byQuery").bind("click",function(){
    $(this).css({color:"green","font-weight":"bold"});  
    $('.divider').addClass('hidden');
    $('#querybox').removeClass('hidden');
 });
