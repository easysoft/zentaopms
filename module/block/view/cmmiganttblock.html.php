<div class="panel-body">
  <div id='cmmiGantt'>
    <?php echo html::select('cmmiGanttProductID', $products, $productID, "class='form-control chosen'"); ?>
    <div class='gantt clearfix'>
      <div class='gantt-plans pull-left'></div>
      <div class='gantt-container scrollbar-hover'>
        <div class='gantt-canvas'></div>
      </div>
    </div>
  </div>
  <style>
  .block-cmmigantt > .panel-body {overflow: visible!important;}
  #cmmiGantt {position: relative;}
  #cmmiGanttProductID_chosen {position: absolute; top: -39px; left: 120px; width: 150px!important;}
  [lang="zh-cn"] #cmmiGanttProductID_chosen {left: 85px}
  .gantt-plans {padding: 20px 0 12px}
  .gantt-plan {margin-top: 10px; line-height: 20px;}
  .gantt-container {position: absolute; left: 100px; top: 0; right: 0; bottom: -10px; overflow-x: auto; padding-top: 20px;}
  .gantt-canvas {border: 1px solid #dddee4; border-style: solid dotted; position: relative}
  .gantt-row {height: 50px; position: relative; z-index: 1}
  .gantt-row:hover {background-color: rgba(0,0,0,.05);}
  .gantt-bar {height: 15px; background: #dddee4; position: absolute; left: 0; top: 10px}
  .gantt-bar-progress {height: 15px; margin-bottom: 5px}
  .gantt-task-info {white-space: nowrap; width: 100%; overflow: visible;}
  .gantt-col {position: absolute; z-index: 0; top: 0; border-right: 1px dotted #dddee4;}
  .gantt-col-time {position: absolute; top: -18px; left: 0}
  </style>
  <script>
  function initCmmiGanttBlock()
  {
      var ganttData = <?php echo $plans; ?>;
      if(!ganttData.data) ganttData = {data: []};

      var plans = [];
      var tasks = [];
      var plansMap = {};
      var startDatetime = Number.MAX_SAFE_INTEGER;
      var endDatetime = 0;
      var minTimeGap = Number.MAX_SAFE_INTEGER;
      var $gantt = $('#cmmiGantt');
      var ONE_DAY = 24 * 3600 * 1000;
      var TIME_GAP_STEP = 7;
      var MIN_COL_WIDTH = 60;

      $.each(ganttData.data, function(index, item)
      {
          plansMap[item.id] = item;
          if(item.type === 'plan' && item.parent === '0')
          {
              item.startDatetime = createDatetime(item.start_date);
              item.endDatetime = createDatetime(item.deadline);
              startDatetime = Math.min(startDatetime, item.startDatetime);
              endDatetime = Math.max(endDatetime, item.endDatetime);
              minTimeGap = Math.min(minTimeGap, endDatetime - startDatetime);
              item.tasks = [];
              item.completeTasks = [];
              item.progress = 0;
              plans.push(item);
          }
          else if(item.type === 'task')
          {
              item.progress = Number.parseInt(item.taskProgress.replace('%', ''), 10);
              tasks.push(item);
          }
      });

      $.each(tasks, function(index, task)
      {
          var plan = plansMap[task.parent];
          while(plan.parent !== '0') plan = plansMap[plan.parent];
          plan.progress += task.progress;
          if(task.progress === 100) plan.completeTasks.push(task);
          plan.tasks.push(task);
      });

      var $plans = $gantt.find('.gantt-plans');
      var $ganttContainer = $gantt.find('.gantt-container');
      var $ganttCanvas = $gantt.find('.gantt-canvas');
      var themeColor = $.getThemeColor('primary');
      var days = Math.ceil((endDatetime - startDatetime) / ONE_DAY);
      var canvasHeight = plans.length * 50 + 10;
      minTimeGap = Math.max(1, Math.ceil(minTimeGap / ONE_DAY));

      // Update gantt plans and bars
      $.each(plans, function(index, plan)
      {
          plan.progress = !plan.tasks.length ? 0 : plan.progress / plan.tasks.length;
          var $plan = $('<div class="gantt-plan"></div>');
          $plan.append('<div class="strong">' + plan.text + '</div>');
          $plan.append('<div class="text-muted small"><?php echo $lang->programplan->planPercent?> ' + plan.percent + '%</div>');
          $plans.append($plan);

          var $bar = $('<div class="gantt-bar"></div>');
          $('<div class="gantt-bar-progress bg-primary"></div>').css(
          {
              width: plan.progress + '%',
              background: themeColor,
          }).appendTo($bar);
          $bar.append('<div class="gantt-task-info text-muted small"><?php echo $lang->programplan->task;?> ' + plan.completeTasks.length + '/' + plan.tasks.length + '</div>').attr('title', $.zui.formatDate(plan.startDatetime, 'yyyy-MM-dd') + '~' + $.zui.formatDate(plan.endDatetime, 'yyyy-MM-dd'));
          var $row = $('<div class="gantt-row" data-id="' + plan.id + '"></div>').append($bar);
          $ganttCanvas.append($row);
      });

      // Layout gantt container
      $ganttContainer.css('left', $plans.width() + 15);
      $ganttCanvas.css('height', canvasHeight);

      var $cmmiGanttProductID = $('#cmmiGanttProductID');
      if(!$cmmiGanttProductID.data('chosen')) $cmmiGanttProductID.chosen();
      $cmmiGanttProductID.on('change', function()
      {
          $.get(createLink('product', 'ajaxSetState', 'productID=' + $cmmiGanttProductID.val()), function()
          {
              refreshBlock($cmmiGanttProductID.closest('.panel'));
          });
      });

      layoutGantt();
      $(window).on('resize', layoutGantt);
      setTimeout(layoutGantt, 100);

      function layoutGantt()
      {
          var minWidth = $ganttContainer.width();
          var timeGap = minTimeGap < TIME_GAP_STEP ? minTimeGap : Math.floor(minTimeGap / TIME_GAP_STEP) * TIME_GAP_STEP;
          var colsCount = Math.ceil(days / timeGap);
          var canvasWidth = Math.max(minWidth, colsCount * MIN_COL_WIDTH);
          var colWidth = Math.floor(canvasWidth / colsCount);
          var pxPerMs = colWidth / (timeGap * ONE_DAY);
          $ganttCanvas.css('width', canvasWidth).find('.gantt-col').remove();
          for (var i = 0; i < colsCount; ++i)
          {
              var $col = $('<div class="gantt-col"></div>');
              $col.css(
              {
                  left: i * colWidth,
                  width: colWidth,
                  height: canvasHeight
              });
              var colTime = startDatetime + i * timeGap * ONE_DAY;
              $col.append('<div class="gantt-col-time text-muted small">' + $.zui.formatDate(colTime, 'MM/dd') + '</div>');
              $ganttCanvas.append($col);
          }

          $.each(plans, function(index, plan)
          {
              var $planRow = $gantt.find('.gantt-row[data-id="' + plan.id + '"]');
              $planRow.find('.gantt-bar').css(
              {
                  left: Math.floor((plan.startDatetime - startDatetime) * pxPerMs),
                  width: Math.floor((plan.endDatetime - plan.startDatetime) * pxPerMs)
              });
          });
      }

      function createDatetime(dateStr)
      {
          dateStr = dateStr.split('-');
          var year = Number.parseInt(dateStr[0].length > 3 ? dateStr[0] : dateStr[2], 10);
          var month = Number.parseInt(dateStr[1], 10);
          var day = Number.parseInt(dateStr[2].length > 3 ? dateStr[0] : dateStr[2], 10);
          return new Date(year, month - 1, day).getTime();
      }
  }
  initCmmiGanttBlock();
  </script>
</div>
