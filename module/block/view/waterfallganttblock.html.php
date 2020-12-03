<div class="panel-body">
  <div id='waterfallGantt'>
    <?php if(count($products) >= 2): ?>
    <?php echo html::select('waterfallGanttProductID', $products, $productID, "class='form-control chosen'");?>
    <span id="ganttProductTips"><i>* </i><?php echo $lang->block->selectProduct;?></span>
    <?php endif;?>
    <?php if(empty($plans['data'])): ?>
    <div class='empty-tip'><?php echo $lang->programplan->noData;?></div>
    <?php else:?>
    <div class='gantt clearfix'>
      <div class='gantt-plans pull-left'></div>
      <div class='gantt-container scrollbar-hover'>
        <div class='gantt-canvas'>
          <div class='gantt-today'><div><?php echo $lang->programplan->today; ?></div></div>
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
  <style>
  #ganttProductTips{position: absolute; top: -32px; left: 240px; opacity: 0.5;}
  #ganttProductTips i{vertical-align: middle;}
  .block-waterfallgantt > .panel-body {overflow: visible!important}
  #waterfallGantt {position: relative}
  #waterfallGanttProductID_chosen {position: absolute; top: -39px; left: 120px; width: 150px!important}
  [lang="zh-cn"] #waterfallGanttProductID_chosen {left: 85px}
  .gantt-plans {padding: 20px 0 22px; max-height: 380px; overflow: hidden;}
  .gantt-plan {width: 70px; height: 50px; line-height: 50px}
  .gantt-plan div {overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
  .gantt-container {position: absolute; left: 100px; top: 0; right: 0; bottom: -10px; overflow: auto; padding-top: 20px; max-height: 380px;}
  .gantt-canvas {border: 1px solid #dddee4; border-style: solid dotted; position: relative}
  .gantt-row {height: 50px; position: relative; z-index: 1}
  .gantt-row:hover {background-color: rgba(0,0,0,.05)}
  .gantt-bar {height: 15px; background: #dddee4; position: absolute; left: 0; top: 10px}
  .gantt-bar-progress {height: 15px; margin-bottom: 5px}
  .gantt-task-info {white-space: nowrap; width: 100%; overflow: visible}
  .gantt-col {position: absolute; z-index: 0; top: 0; border-right: 1px dotted #dddee4}
  .gantt-col-time {position: absolute; top: -18px; left: 0}
  .gantt-today {position: absolute; top: -16px; left: 0; bottom: 0; border-left: #00da88 dotted 1px;}
  .gantt-today > div {position: absolute; top: 0; left: 0; font-size: 12px; line-height: 14px; padding: 0 3px; background: #00da88; color: #fff; white-space: nowrap; z-index: 10;}
  </style>
  <script>
  function initWaterfallGanttBlock()
  {
      /* Init product select control */
      var $waterfallGanttProductID = $('#waterfallGanttProductID');
      $waterfallGanttProductID.on('change', function()
      {
          $.get(createLink('product', 'ajaxSetState', 'productID=' + $waterfallGanttProductID.val()), function()
          {
              refreshBlock($waterfallGanttProductID.closest('.panel'));
          });
      });

      <?php if(!empty($plans['data'])): ?>
      var ganttData = <?php echo json_encode($plans['data']); ?>;
      if(!ganttData) return;

      var plans         = [];
      var tasks         = [];
      var plansMap      = {};
      var startDatetime = Number.MAX_SAFE_INTEGER;
      var endDatetime   = 0;
      var minTimeGap    = Number.MAX_SAFE_INTEGER;
      var $gantt        = $('#waterfallGantt');
      var ONE_DAY       = 24 * 3600 * 1000;
      var TIME_GAP_STEP = 7;
      var MIN_COL_WIDTH = 60;

      $.each(ganttData, function(index, item)
      {
          plansMap[item.id] = item;
          if(item.type == 'plan' && item.parent == '0')
          {
              item.startDatetime = createDatetime(item.start_date);
              item.endDatetime   = createDatetime(item.deadline);
              startDatetime      = Math.min(startDatetime, item.startDatetime);
              endDatetime        = Math.max(endDatetime, item.endDatetime);
              minTimeGap         = Math.min(minTimeGap, endDatetime - startDatetime);
              item.tasks         = [];
              item.completeTasks = [];
              item.progress      = 0;
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
          while(plan.parent > 0) plan = plansMap[plan.parent];
          plan.progress += task.progress;
          if(task.progress === 100) plan.completeTasks.push(task);
          plan.tasks.push(task);
      });

      var $plans          = $gantt.find('.gantt-plans');
      var $ganttContainer = $gantt.find('.gantt-container');
      var $ganttCanvas    = $gantt.find('.gantt-canvas');
      var themeColor      = $.getThemeColor('primary');
      var canvasHeight    = plans.length * 50 + 10;

      var days   = Math.ceil((endDatetime - startDatetime) / ONE_DAY);
      minTimeGap = Math.max(1, Math.ceil(minTimeGap / ONE_DAY));

      /* Update gantt plans and bars */
      $.each(plans, function(index, plan)
      {
          plan.progress = !plan.tasks.length ? 0 : plan.progress / plan.tasks.length;
          var $plan = $('<div class="gantt-plan"></div>');
          $plan.append('<div class="strong" title="' + plan.text + '">' + plan.text + '</div>');
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

      /* Layout gantt container */
      $ganttContainer.css('left', $plans.width() + 15);
      $ganttCanvas.css('height', canvasHeight);

      /* Layout gantt */
      layoutGantt();
      $(window).on('resize', layoutGantt);
      setTimeout(layoutGantt, 100);

      /* Bind events */
      $ganttContainer.on('scroll', function()
      {
          $plans.scrollTop($ganttContainer.scrollTop());
      });

      /**
       * Layout gantt
       *
       * @return {void}
       */
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
                  left:   i * colWidth,
                  width:  colWidth,
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
                  left:  Math.floor((plan.startDatetime - startDatetime) * pxPerMs),
                  width: Math.floor((plan.endDatetime - plan.startDatetime) * pxPerMs)
              });
          });

          $gantt.find('.gantt-today').css('left', (Date.now() - startDatetime) * pxPerMs);
      }

      /**
       * Create date from string
       *
       * @param {String} dateStr like '2020-08-02'
       * @return {Number} Date timestramp
       */
      function createDatetime(dateStr)
      {
          dateStr   = dateStr.split('-');
          var year  = Number.parseInt(dateStr[0].length > 3 ? dateStr[0] : dateStr[2], 10);
          var month = Number.parseInt(dateStr[1], 10);
          var day   = Number.parseInt(dateStr[2].length > 3 ? dateStr[0] : dateStr[2], 10);
          return new Date(year, month - 1, day).getTime();
      }

      <?php endif;?>
  }

  initWaterfallGanttBlock();
  </script>
</div>
