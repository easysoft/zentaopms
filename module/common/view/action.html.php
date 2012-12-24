<script language='Javascript'>
var fold   = '<?php echo $lang->fold;?>';
var unfold = '<?php echo $lang->unfold;?>';
function switchChange(historyID)
{
    changeClass = $('#switchButton' + historyID).attr('class');
    if(changeClass.indexOf('change-show') > 0)
    {
        $('#switchButton' + historyID).attr('class', changeClass.replace('change-show', 'change-hide'));
        $('#changeBox' + historyID).show();
        $('#changeBox' + historyID).prev('.changeDiff').show();
    }
    else
    {
        $('#switchButton' + historyID).attr('class', changeClass.replace('change-hide', 'change-show'));
        $('#changeBox' + historyID).hide();
        $('#changeBox' + historyID).prev('.changeDiff').hide();
    }
}

function toggleStripTags(obj)
{
    var diffClass = $(obj).attr('class');
    if(diffClass.indexOf('diff-all') > 0)
    {
        $(obj).attr('class', diffClass.replace('diff-all', 'diff-short'));
        $(obj).attr('title', '<?php echo $lang->action->textDiff?>');
    }
    else
    {
        $(obj).attr('class', diffClass.replace('diff-short', 'diff-all'));
        $(obj).attr('title', '<?php echo $lang->action->original?>');
    }
    var boxObj  = $(obj).next();
    var oldDiff = '';
    var newDiff = '';
    $(boxObj).find('blockquote').each(function(){
        oldDiff = $(this).html();
        newDiff = $(this).next().html();
        $(this).html(newDiff);
        $(this).next().html(oldDiff);
    })
}

function toggleShow(obj)
{
    var orderClass = $(obj).find('span').attr('class');
    if(orderClass == 'change-show')
    {
        $(obj).find('span').attr('class', 'change-hide');
    }
    else
    {
        $(obj).find('span').attr('class', 'change-show');
    }
    $('.changes').each(function(){
        var box = $(this).parent();
        var switchButtonID = ($(box).find('span').find("input[type='button']").attr('id'));
        switchChange(switchButtonID.replace('switchButton', ''));
    })
}

function toggleOrder(obj)
{
    var orderClass = $(obj).find('span').attr('class');
    if(orderClass == 'log-asc')
    {
        $(obj).find('span').attr('class', 'log-desc');
    }
    else
    {
        $(obj).find('span').attr('class', 'log-asc');
    }
    $("#historyItem li").reverseOrder();
}

$(function(){
    var diffButton = "<input type='button' onclick='toggleStripTags(this)' class='hidden changeDiff diff-all' title='<?php echo $lang->action->original?>' value='&nbsp;' >";
    var newBoxID = ''
    var oldBoxID = ''
    $('blockquote').each(function(){
        newBoxID = $(this).parent().attr('id');
        if(newBoxID != oldBoxID) 
        {
            oldBoxID = newBoxID;
            if($(this).html() != $(this).next().html()) $(this).parent().before(diffButton);
        }
    })
})
</script>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<script src='<?php echo $jsRoot;?>jquery/reverseorder/raw.js' type='text/javascript'></script>

<?php if(!isset($actionTheme)) $actionTheme = 'fieldset';?>
<?php if($actionTheme == 'fieldset'):?>
<div id='actionbox'>
<fieldset>
  <legend>
  <?php echo $lang->history?>
    <span onclick='toggleOrder(this)' class='hand'> <?php echo "<span title='$lang->reverse' class='log-asc'>&nbsp;</span>";?></span>
    <span onclick='toggleShow(this);' class='hand'><?php echo "<span title='$lang->switchDisplay' class='change-show'>&nbsp;</span>";?></span>
  </legend>
<?php else:?>
<table class='table-1' id='actionbox'>
  <caption>
    <?php echo $lang->history?>
    <span onclick='$("#historyItem li").reverseOrder();' class='hand'> <?php echo "<span title='$lang->reverse' class='log-asc'>&nbsp;</span>";?></span>
    <span onclick='toggleShow();' class='hand'><?php echo "<span title='$lang->switchDisplay' class='change-show'>&nbsp;</span>";?></span>
  </caption>
  <tr><td>
<?php endif;?>

  <ol id='historyItem'>
    <?php $i = 1; ?>
    <?php foreach($actions as $action):?>
    <li value='<?php echo $i ++;?>'>
      <?php
      if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
      if($action->action == 'assigned' and isset($users[$action->extra]) ) $action->extra = $users[$action->extra];
      if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);
      ?>
      <span>
        <?php $this->action->printAction($action);?>
        <?php if(!empty($action->history)) echo html::commonButton('&nbsp;', "id=switchButton$i class='hand change-show' onclick=switchChange($i)");?>
      </span>
      <?php if(!empty($action->comment) or !empty($action->history)):?>
      <?php if(!empty($action->comment)) echo "<div class='history'>";?>
        <div class='changes hidden' id='changeBox<?php echo $i;?>'>
        <?php echo $this->action->printChanges($action->objectType, $action->history);?>
        </div>
        <?php if($action->comment) echo nl2br($action->comment);?>
      <?php if(!empty($action->comment)) echo "</div>";?>
      <?php endif;?>
    </li>
    <?php endforeach;?>
  </ol>

<?php if($actionTheme == 'fieldset'):?>
</fieldset>
<?php else:?>
</td></tr></table>
<?php endif;?>
</div>
