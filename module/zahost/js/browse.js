$('#hostList tr[data-status="wait"]').hover(function(){
  $(this).find('.init').tooltip('toggle');
},function(){
  $(this).find('.init').tooltip('hide');
});
