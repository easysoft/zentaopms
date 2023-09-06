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

window.handleCheckboxChange = function($el)
{
    var isChecked = $el.is(":checked");
    var value = $el.val();

    if(isChecked)
    {
      if(window.checkedList.length >= 10)
      {
        $el.prop('checked', false);
        return zui.Messager.show(
          {
            content: maxSelectMsg.replace('%s', maxSelectNum),
            icon: 'icon-exclamation-pure',
            iconClass: 'center w-6 h-6 rounded-full m-0 warning',
            contentClass: 'text-lg font-bold',
            close: false,
            className: 'p-6 bg-white text-black gap-2 messager-fail',
          });
      }
      window.checkedList.push(value);
      $el.closest('.checkbox-primary').addClass('metric-current');
    }
    else
    {
      window.checkedList = window.checkedList.filter(function(id){return id != value})
      $el.closest('.checkbox-primary').removeClass('metric-current');
    }
}

window.handleNavMenuClick = function($el)
{
    var scope = $el.attr('id');
    var itemSelector = 'menu.nav-ajax .nav-item a';
    $.get($.createLink('metric', 'ajaxGetMetrics', 'scope=' + scope), function(resp)
    {
      var metrics = JSON.parse(resp);
      var total = metrics.length;

      $(itemSelector).removeClass('active');
      $(itemSelector).find('span.label').remove();
      $el.addClass('active');
      $el.append(`<span class="label size-sm rounded-full white">${total}</span>`);

      window.renderCheckList(metrics);
    });
}

window.afterPageUpdate = function($target, info, options)
{
  window.checkedList = [current.id + ''];
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
