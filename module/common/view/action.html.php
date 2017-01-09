<style>
#actionbox a{font-weight:normal}
.col-side fieldset#actionbox{padding-right:5px;}
.col-side #actionbox #historyItem li span.item{white-space:nowrap}
.changes blockquote{font-family: monospace, serif;}
</style>
<script language='Javascript'>
var fold   = '<?php echo $lang->fold;?>';
var unfold = '<?php echo $lang->unfold;?>';
function switchChange(historyID)
{
    $swbtn = $('#switchButton' + historyID);
    $showTag = $swbtn.find('.change-show');
    if($showTag.length)
    {
        $swbtn.closest('li').addClass('show-changes');
        $showTag.removeClass('change-show').addClass('change-hide');
        $('#changeBox' + historyID).show();
        $('#changeBox' + historyID).prev('.changeDiff').show();
    }
    else
    {
        $swbtn.closest('li').removeClass('show-changes');
        $swbtn.find('.change-hide').removeClass('change-hide').addClass('change-show');
        $('#changeBox' + historyID).hide();
        $('#changeBox' + historyID).prev('.changeDiff').hide();
    }
}

function toggleStripTags(obj)
{
    var btn = $(obj);
    var diffTag = btn.find('.icon-file-code');
    if(diffTag.length)
    {
        diffTag.removeClass('icon-file-code').addClass('diff-short');
        btn.attr('title', '<?php echo $lang->action->textDiff?>');
    }
    else
    {
        btn.find('.diff-short').removeClass('diff-short').addClass('icon-file-code');
        btn.attr('title', '<?php echo $lang->action->original?>');
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
    $showTag = $(obj).find('.change-show');
    if($showTag.length)
    {
        $showTag.removeClass('change-show').addClass('change-hide');
        $('#historyItem > li:not(.show-changes) .switch-btn').click();
    }
    else
    {
        $(obj).find('.change-hide').removeClass('change-hide').addClass('change-show');
        $('#historyItem > li.show-changes .switch-btn').click();
    }
}

function toggleOrder(obj)
{
    var $orderTag = $(obj).find('.log-asc');
    if($orderTag.length)
    {
        $orderTag.attr('class', 'icon- log-desc');
    }
    else
    {
        $(obj).find('.log-desc').attr('class', 'icon- log-asc');
    }
    $("#historyItem li").reverseOrder();
    window.editor['lastComment'].remove();
    initKindeditor();
}

function toggleComment(actionID)
{
    $('.comment' + actionID).toggle();
    $('#lastCommentBox').toggle();
    $('.ke-container').css('width', '100%');
}

$(function()
{
    var diffButton = "<a href='javascript:;' onclick='toggleStripTags(this)' class='changeDiff btn-icon' style='display:none;' title='<?php echo $lang->action->original?>'><i class='icon- icon-file-code'></i></a>";
    var newBoxID = ''
    var oldBoxID = ''
    $('blockquote').each(function()
    {
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
<fieldset id='actionbox' class='actionbox'>
  <legend>
    <i class='icon-time'></i> 
    <?php echo $lang->history?>
    <a class='btn-icon' href='javascript:;' onclick='toggleOrder(this)'> <?php echo "<span title='$lang->reverse' class='log-asc icon-'></span>";?></a>
    <a class='btn-icon' href='javascript:;' onclick='toggleShow(this);'><?php echo "<span title='$lang->switchDisplay' class='change-show icon-'></span>";?></a>
  </legend>
<?php else:?>
<div id='actionbox' class='actionbox panel panel-sm'>
  <div class='panel-heading'>
    <i class='icon-time'></i> <strong><?php echo $lang->history?></strong>
    <div class='panel-actions'>
      <a class='btn btn-mini' href='javascript:;' onclick='toggleOrder(this);' class='hand'> <?php echo "<span title='$lang->reverse' class='log-asc icon-'></span>";?></a>
      <a class='btn btn-mini' href='javascript:;' onclick='toggleShow(this);' class='hand'><?php echo "<span title='$lang->switchDisplay' class='change-show icon-'></span>";?></a>
    </div>
  </div>
  <div class='panel-body'>
<?php endif;?>
  <ol id='historyItem'>
    <?php $i = 1; ?>
    <?php foreach($actions as $action):?>
    <?php $canEditComment = (end($actions) == $action and $action->comment and $this->methodName == 'view' and $action->actor == $this->app->user->account);?>
    <li value='<?php echo $i ++;?>'>
      <?php
      if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
      if($action->action == 'assigned' and isset($users[$action->extra]) ) $action->extra = $users[$action->extra];
      if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);
      ?>
      <span class='item'>
        <?php $this->action->printAction($action);?>
        <?php if(!empty($action->history)) echo "<a id='switchButton$i' class='switch-btn btn-icon' onclick='switchChange($i)' href='javascript:;'><i class='change-show icon-'></i></a>";?>
      </span>
      <?php if(!empty($action->comment) or !empty($action->history)):?>
      <?php if(!empty($action->comment)) echo "<div class='history'>";?>
        <div class='changes hide alert' id='changeBox<?php echo $i;?>'>
        <?php echo $this->action->printChanges($action->objectType, $action->history);?>
        </div>
        <?php if($canEditComment):?>
        <span class='pull-right comment<?php echo $action->id;?>'><?php echo html::a('javascript:toggleComment(' . $action->id . ')', '<i class="icon-pencil"></i>', '', "class='btn btn-mini' style='border:none'")?></span>
        <?php endif;?>
        <?php 
        if($action->comment) 
        {
            echo "<div class='article-content comment$action->id'>";
            echo strip_tags($action->comment) == $action->comment ? nl2br($action->comment) : $action->comment; 
            echo "</div>";
        }
        ?>
        <?php if($canEditComment):?>
        <div class='hide' id='lastCommentBox'>
          <form method='post' action='<?php echo $this->createLink('action', 'editComment', "actionID=$action->id")?>'>
            <table align='center' class='table table-form bd-0'>
              <tr><td style='padding-right: 0'><?php echo html::textarea('lastComment', htmlspecialchars($action->comment), "style='width:100%;height:100px'");?></td></tr>
              <tr><td><?php echo html::submitButton() . html::a("javascript:toggleComment($action->id)", $lang->goback, '', "class='btn'");?></td></tr>
            </table>
          </form>
        </div>
        <?php endif;?>

        <?php if(!empty($action->comment)) echo "</div>";?>
      <?php endif;?>
    </li>
    <?php endforeach;?>
  </ol>

<?php if($actionTheme == 'fieldset'):?>
</fieldset>
<?php else:?>
</div></div>
<?php endif;?>
