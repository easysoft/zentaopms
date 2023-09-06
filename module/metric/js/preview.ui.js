window.renderHeight = function()
{
    return $('.table-side').height();
}

window.generateCheckItem = function(text, value, typeClass, isChecked)
{
  var checked = isChecked ? 'checked=""' : '';
  return `<div class="${typeClass}">
            <input type="checkbox" id="metric${value}" name="metric" ${checked} value="${value}">
            <label for="metric${value}">${text}</label>
          </div>`;
}

window.renderCheckList = function(metrics)
{
  console.log(window.checkedList);
  $('.side .check-list-metric').empty();
  var metricsHtml = metrics.map(function(metric)
  {
    var isChecked = window.checkedList.includes(metric.id + '');
    var typeClass = isChecked ? 'metric-current' : '';
    typeClass += ' font-medium checkbox-primary';
    return window.generateCheckItem(metric.name, metric.id, typeClass, isChecked);
  }).join('');
  $('.side .check-list-metric').html(metricsHtml);
}

window.checkedList = [];

$(document).ready()
{
  renderDTable();
  $(document).on('change', '.checkbox-primary input[type="checkbox"]', function()
  {
    var isChecked = $(this).is(":checked");
    var value = $(this).val();

    if(isChecked)
    {
      window.checkedList.push(value);
      $(this).closest('.checkbox-primary').addClass('metric-current');
    }
    else
    {
      window.checkedList = window.checkedList.filter(function(id){return id != value})
      $(this).closest('.checkbox-primary').removeClass('metric-current');
    }
  });

  window.checkedList.push('' + current.id);

  var itemSelector = 'menu.nav-ajax .nav-item a';
  $(document).off('click', itemSelector)
  $(document).on('click', itemSelector, function()
  {
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

      window.renderCheckList(metrics);
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
