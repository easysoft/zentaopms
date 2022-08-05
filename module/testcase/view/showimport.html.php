<?php include $app->getModuleRoot() . 'port/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->port->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post' id='portform'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-70px'> <?php echo $lang->port->id?></th>
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
        <?php include $app->getModuleRoot() . 'port/view/tfoot.html.php';?>
      </tfoot>
    </table>
    <?php if(!$insert and $dataInsert === '') include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'port/view/footer.html.php';?>
<script>
$('#showData').on('change', '.picker-select', function(e)
{
    var id        = $(this).attr('id');
    var field     = $(this).attr('data-field');
    var moduleID  = $(this).val();
    var index     = Number(id.replace(/[^\d]/g, " "));
    var productID = <?php echo $productID;?>;

    if(field === 'module')
    {
        var storyLink    = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=0&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&executionID=0&number=' + index);
        $.get(storyLink, function(stories)
        {
            $('#story' + index).next('.picker').remove();
            $('#story' + index).replaceWith(stories);
            $('#story' + index).picker({chosenMode: true});
            $('#story' + index).attr('isInit', true);
        })
    }
});
</script>
