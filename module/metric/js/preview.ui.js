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
  renderDTable();
  if(viewType == 'multiple') renderCheckedLabel(metricList);
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

function renderCheckedLabel(labels)
{
  $('.checked-label-content').empty();
  var multi = labels.length > 1;
  var width = Math.floor($('.checked-label-content').width());
  var left  = width;

  var labelClass = 'label circle gray-pale';
  if(multi) labelClass += ' gray-pale-withdelete';

  for(var i = 0; i < labels.length; i++)
  {
    var label = labels[i];
    var html = '<span class="' + labelClass + '" metric-id="' + label.id + '">';
    html    += '<div class="gray-pale-div">' + label.name + '</div>';
    if(multi) html += '<button type="button" class="btn picker-deselect-btn size-sm square ghost"><span class="close"></span></button>';
    html    += '</span>';

    $('.checked-label-content').append(html);

    var $label     = $('.checked-label-content').find('[metric-id="' + label.id + '"]');
    var labelWidth = Math.ceil($label.width() + parseInt($label.css('padding-left')) + parseInt($label.css('padding-right')) + parseInt($label.css('margin-left')) + parseInt($label.css('margin-right')));

    left = left - labelWidth;
    if(left <= 0)
    {
      var $div     = $('.checked-label-content').find('[metric-id="' + label.id + '"]').find('.gray-pale-div');
      var divWidth = $div.width();

      if(divWidth < -left)
      {
        // 如果剩下的空间一点字都显示不下了，就换行
        left = width - labelWidth;
      }
      else
      {
        $div.width(Math.floor($div.width()) - Math.ceil(-left) - 1);

        // 换行了，重置left
        left = width;
      }
    }
  }
}
