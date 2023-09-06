window.renderHeight = function()
{
    return $('.table-side').height();
}

$(document).ready()
{
  renderDTable();
  $(document).on('change', '.checkbox-primary input[type="checkbox"]', function()
  {
    $('.checkbox-primary input[type="checkbox"]').each(function()
    {
      if($(this).is(":checked")) $(this).closest('.checkbox-primary').addClass('metric-current');
      if($(this).is(":not(:checked)")) $(this).closest('.checkbox-primary').removeClass('metric-current');
    });
  });

  var itemSelector = 'menu.nav-ajax .nav-item a';
  $(document).off('click', itemSelector)
  $(document).on('click', itemSelector, function()
  {
    console.log(this);
    var that  = this;
    var scope = $(this).attr('id');
    $.get($.createLink('metric', 'ajaxGetMetrics', 'scope=' + scope), function(resp)
    {
      var metrics = JSON.parse(resp);
      var total = metrics.length;

      $(itemSelector).removeClass('active');
      $(itemSelector).find('span.label').remove();
      $(that).addClass('active');
      $(that).append(`<span class="label size-sm rounded-full white">${total}</span>`);
    });
  });
}

function renderDTable()
{
    $('.dtable').empty();

    if(!resultHeader || !resultData) return;
    new zui.DTable('.dtable',
    {
        responsive: true,
        bordered: true,
        scrollbarHover: true,
        height: function() { return $('.table-side').height(); },
        cols: resultHeader,
        data: resultData,
    });

}
