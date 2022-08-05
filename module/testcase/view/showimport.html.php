<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
    $requiredFields = $datas->requiredFields;
    $allCount       = $datas->allCount;
    $allPager       = $datas->allPager;
    $pagerID        = $datas->pagerID;
    $isEndPage      = $datas->isEndPage;
    $maxImport      = $datas->maxImport;
    $dataInsert     = $datas->dataInsert;
    $fields         = $datas->fields;
    $suhosinInfo    = $datas->suhosinInfo;
    $model          = $datas->model;
    $datas          = $datas->datas;
    $appendFields   = $this->session->appendFields;
    $notEmptyRule   = $this->session->notEmptyRule;
    $insert         = $this->session->insert;
?>
<style>
form{overflow-x: scroll}
.c-pri, .c-estStarted, .c-deadline{width:100px;}
.c-estimate {width:150px;}
.c-story{width:150px;}
.c-team {width:100px; padding:0px 0px 8px 0px !important;}
.c-estimate-1 {width:50px;padding:0px 0px 8px 8px !important;}
</style>
<?php if(!empty($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo?></div>
<?php elseif(empty($maxImport) and $allCount > $this->config->file->maxImport):?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->task->import;?></h2>
  </div>
  <p><?php echo sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport));?></p>
  <p><?php echo html::commonButton($lang->import, "id='import'", 'btn btn-primary');?></p>
</div>
<script>
$(function()
{
    $('#maxImport').keyup(function()
    {
        if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($('#allCount').html()) / parseInt($('#maxImport').val())));
    });

    $('#import').click(function()
    {
        $.cookie('maxImport', $('#maxImport').val());
        location.href = "<?php echo $this->app->getURI()?>";
    })
});
</script>
<?php else:?>
<?php js::set('requiredFields', $requiredFields);?>
<?php js::set('allCount', $allCount);?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->task->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post' id='portform'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-70px'> <?php echo $lang->task->id?></th>

          <?php foreach($fields as $key => $value):?>

          <?php if($key == 'stepDesc' or $key == 'stepExpect'):?>
          <?php if($key == 'stepExpect') continue;?>
          <th class='c-step'>
            <table class='w-p100 table-borderless'>
              <tr>
                <th class="no-padding"><?php echo $fields['stepDesc']['title']?></th>
                <th class="no-padding"><?php echo $fields['stepExpect']['title']?></th>
              </tr>
            </table>
          </th>

          <?php elseif($value['control'] != 'hidden'):?>
          <th class='c-<?php echo $key?>'  id='<?php echo $key;?>'>  <?php echo $value['title'];?></th>

          <?php endif;?>
          <?php endforeach;?>
          <?php
          if(!empty($appendFields))
          {
              foreach($appendFields as $field)
              {
                  if(!$field->show) continue;

                  $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
                  $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
                  echo "<th class='$required' style='width: $width'>$field->name</th>";
              }
          }
          ?>
        </tr>
      </thead>
      <tbody>
      </tbody>

      <tfoot class='hidden'>
        <tr>
          <td colspan='10' class='text-center form-actions'>
            <?php
            $submitText  = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
            $isStartPage = $pagerID == 1 ? true : false;
            if(!$insert and $dataInsert === '')
            {
                echo "<button type='button' data-toggle='modal' data-target='#importNoticeModal' class='btn btn-primary btn-wide'>{$submitText}</button>";
            }
            else
            {
                echo html::submitButton($submitText);
                if($dataInsert !== '') echo html::hidden('insert', $dataInsert);
            }
            echo html::hidden('isEndPage', $isEndPage ? 1 : 0);
            echo html::hidden('pagerID', $pagerID);
            echo ' &nbsp; ' . html::a("javascript:history.back(-1)", $lang->goback, '', "class='btn btn-back btn-wide'");
            echo ' &nbsp; ' . sprintf($lang->file->importPager, $allCount, $pagerID, $allPager);
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php if(!$insert and $dataInsert === '') include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
  </form>
</div>
<?php endif;?>
<script>

$.get(createLink('port', 'ajaxGetTbody','model=<?php echo $model;?>&lastID=0&pagerID=<?php echo $pagerID;?>'), function(data)
{
    $('#showData > tbody').append(data);
    if($('#showData tbody').find('tr').hasClass('showmore') === false) $('#showData tfoot').removeClass('hidden');
    $('#showData tbody').find('.picker-select').picker({chosenMode: true});
    $("input[name^='product']").val(<?php echo $productID?>);
})

window.addEventListener('scroll', this.handleScroll);
function handleScroll(e)
{
    var relative = 500; // 相对距离
    $('tr.showmore').each(function()
    {
        var $showmore = $(this);
        var offsetTop = $showmore[0].offsetTop;
        if(offsetTop == 0) return true;

        if(getScrollTop() + getWindowHeight() >= offsetTop - relative)
        {
            throttle(loadData($showmore), 250)
        }
    })
}

function loadData($showmore)
{
    $showmore.removeClass('showmore');
    var lastID = $showmore.attr('data-id');
    var url    = createLink('port', 'ajaxGetTbody','model=<?php echo $model;?>&lastID=' + lastID + '&pagerID=<?php echo $pagerID;?>');
    $.get(url, function(data)
    {
        $showmore.after(data);
        if($('#showData tbody').find('tr').hasClass('showmore') === false) $('#showData tfoot').removeClass('hidden');
        $('#showData tbody').find('.picker-select.nopicker').picker({chosenMode: true}).removeClass('nopicker');
        $("input[name^='product']").val(<?php echo $productID?>);
    })
}

function throttle(fn, threshhold)
{
    var last;
    var timer;
    threshhold || (threshhold = 250);

    return function()
    {
        var context = this;
        var args = arguments;

        var now = +new Date()

        if (last && now < last + threshhold)
        {
            clearTimeout(timer);
            timer = setTimeout(function ()
            {
                last = now
                fn.apply(context, args)
            }, threshhold)
        }
        else
        {
            last = now
            fn.apply(context, args)
        }
    }
}

function getScrollTop()
{
    return scrollTop = document.body.scrollTop + document.documentElement.scrollTop
}

function getWindowHeight()
{
    return document.compatMode == "CSS1Compat" ? windowHeight = document.documentElement.clientHeight : windowHeight = document.body.clientHeight
}

$('#showData').on('mouseenter', '.picker', function(e){
    var myPicker = $(this);
    var field    = myPicker.prev().attr('data-field');
    var id       = myPicker.prev().attr('id');
    var name     = myPicker.prev().attr('name');
    var index    = Number(name.replace(/[^\d]/g, " "));
    var value    = myPicker.prev().val();

    if($('#' + id).attr('isInit')) return;

    $.ajaxSettings.async = false;
    $.get(createLink('port', 'ajaxGetOptions', 'model=<?php echo $model;?>&field=' + field + '&value=' + value + '&index=' + index), function(data)
    {
        $('#' + id).parent().html(data);
        $('#' + id).picker({chosenMode: true});
        $('#' + id).attr('isInit', true);
        $('#' + id).attr('data-field', field);
    });
    $.ajaxSettings.async = true;
})

$('#showData').on('change', '.picker-select', function(e)
{
    var id = $(this).attr('id');
    var field = $(this).attr('data-field');

    if(field === 'module')
    {
        console.log(123);
    }
});

$(function()
{
    $.fixedTableHead('#showData');
    $("#showData th").each(function()
    {
        if(requiredFields.indexOf(this.id) !== -1) $("#" + this.id).addClass('required');
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
