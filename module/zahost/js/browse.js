$('#hostList tr[data-status="wait"]').hover(function(){
  $(this).find('.inithost').tooltip('toggle');
},function(){
  $(this).find('.inithost').tooltip('hide');
});
