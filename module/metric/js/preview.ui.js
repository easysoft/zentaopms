window.renderHeight = function()
{
    return $('.table-side').height();
}

window.generateCheckItem = function(text, value, isChecked)
{
  var checked = isChecked ? 'checked=""' : '';
  var currentClass = isChecked ? 'metric-current' : '';
  return `<div class="font-medium checkbox-primary ${currentClass}">
            <input type="checkbox" id="metric${value}" name="metric" ${checked} value="${value}">
            <label for="metric${value}">${text}</label>
          </div>`;
}

window.messagerWarning = function(message)
{
  return zui.Messager.show(
  {
    content: message,
    icon: 'icon-exclamation-pure',
    iconClass: 'center w-6 h-6 rounded-full m-0 warning',
    contentClass: 'text-lg font-bold',
    close: false,
    className: 'p-6 bg-white text-black gap-2 messager-fail',
  });
}

window.isMetricChecked = function(id)
{
  return window.checkedList.filter(function(metric){return metric.id == id}).length != 0;
}

window.renderCheckList = function(metrics)
{
  $('.side .check-list-metric').empty();

  var metricsHtml = metrics.map(function(metric)
  {
    var isChecked = window.isMetricChecked(metric.id);
    return window.generateCheckItem(metric.name, metric.id, isChecked);
  }).join('');

  $('.side .check-list-metric').html(metricsHtml);
}

window.updateCheckList = function(id, name, isChecked)
{
  if(isChecked)
  {
     return window.checkedList.push({id: id, name: name});
  }

  window.checkedList = window.checkedList.filter(function(item){return item.id != id});
}

window.updateCheckbox = function(id, isChecked)
{
  var $el = $('.side .metric-tree .check-list input#metric' + id);
  $el.prop('checked', isChecked);
  if(isChecked)
  {
    return $el.closest('.checkbox-primary').addClass('metric-current');
  }

  $el.closest('.checkbox-primary').removeClass('metric-current');
}

window.updateCheckAction = function(id, name, isChecked)
{
  window.updateCheckList(id, name, isChecked);
  window.updateCheckbox(id, isChecked);
  window.renderCheckedLabel();
}

window.handleCheckboxChange = function($el)
{
  var isChecked = $el.is(":checked");
  var value = $el.val();
  var text  = $el.next().text();

  if(isChecked && window.checkedList.length >= 10)
  {
    $el.prop('checked', false);
    return messagerWarning(maxSelectMsg.replace('%s', maxSelectNum));
  }

  window.updateCheckAction(value, text, isChecked);
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
  window.checkedList = [{id:current.id + '', name:current.name}];
  window.renderDTable();
  if(viewType == 'multiple') renderCheckedLabel();
}

window.renderDTable = function()
{
    $('.dtable').remove();
    $('.table-side').append('<div class="dtable"></div>');

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

window.handleRemoveLabel = function(id)
{
  var checkedItem = window.checkedList.find(function(checked){return checked.id == id});
  if(!checkedItem) return;

  window.updateCheckAction(checkedItem.id, checkedItem.name, false);
}

window.renderCheckedLabel = function()
{
  $('.checked-label-content').empty();
  var labels = JSON.parse(JSON.stringify(window.checkedList));
  console.log(labels);
  var multi  = labels.length > 1;
  var width  = Math.floor($('.checked-label-content').width());
  var left   = width;

  var labelClass = 'label circle gray-pale';
  if(multi) labelClass += ' gray-pale-withdelete';

  for(var i = 0; i < labels.length; i++)
  {
    var label = labels[i];
    var html = '<span class="' + labelClass + '" metric-id="' + label.id + '">';
    html    += '<div class="gray-pale-div">' + label.name + '</div>';
    if(multi) html += '<button type="button" class="btn picker-deselect-btn size-sm square ghost" onclick="window.handleRemoveLabel(' + label.id + ')"><span class="close"></span></button>';
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

  $('.checked-tip').text(selectCount.replace('%s', labels.length));
}
