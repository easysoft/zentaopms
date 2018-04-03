<div class="detail histories" id='actionbox'>
  <style>
  #actionbox a{font-weight:normal}
  .col-side #actionbox{padding-right:5px;}
  .col-side #actionbox .histories-list li span.item{white-space:nowrap}
  #commentBox{margin-bottom:8px;}
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
          $swbtn.closest('li').find('.item .changeDiff').show();
      }
      else
      {
          $swbtn.closest('li').removeClass('show-changes');
          $swbtn.find('.change-hide').removeClass('change-hide').addClass('change-show');
          $('#changeBox' + historyID).hide();
          $swbtn.closest('li').find('.item .changeDiff').hide();
      }
  }
  
  function toggleStripTags(obj)
  {
      var btn = $(obj);
      var diffTag = btn.find('.icon-code');
      if(diffTag.length)
      {
          diffTag.removeClass('icon-code').addClass('diff-short');
          btn.attr('title', '<?php echo $lang->action->textDiff?>');
      }
      else
      {
          btn.find('.diff-short').removeClass('diff-short').addClass('icon-code');
          btn.attr('title', '<?php echo $lang->action->original?>');
      }
      var boxObj  = $(obj).closest('li').find('.history-changes');
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
      $showTag = $(obj);
      if($showTag.hasClass('change-show'))
      {
          $showTag.removeClass('change-show').addClass('change-hide');
          $('.histories-list li.show-changes .item .switch-btn').click();
      }
      else
      {
          $showTag.removeClass('change-hide').addClass('change-show');
          $('.histories-list li:not(.show-changes) .item .switch-btn').click();
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
      $(".histories-list li").reverseOrder();
      window.editor['lastComment'].remove();
      initKindeditor();
  }
  
  function toggleComment(actionID)
  {
      if(actionID == '#commentBox')
      {
          $(actionID).toggle();
      }
      else
      {
          $('.comment' + actionID).toggle();
          $('#lastCommentBox').toggle();
          $('.ke-container').css('width', '100%');
      }
  }
  
  $(function()
  {
      var diffButton = "<button tye='button' onclick='toggleStripTags(this)' class='btn btn-mini changeDiff btn-icon' style='display:none;' title='<?php echo $lang->action->original?>'><i class='icon icon-sm icon-code'></i></button>";
      var newBoxID = ''
      var oldBoxID = ''
      $('blockquote').each(function()
      {
          newBoxID = $(this).parent().attr('id');
          if(newBoxID != oldBoxID) 
          {
              oldBoxID = newBoxID;
              if($(this).html() != $(this).next().html()) $(this).closest('li').find('.item').append(diffButton);
          }
      })
  })
  </script>
  <?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
  <script src='<?php echo $jsRoot;?>jquery/reverseorder/raw.js' type='text/javascript'></script>
  <div class="detail-title">
    <?php echo $lang->history?> &nbsp;
    <button type="button" onclick='toggleOrder(this);' class="btn btn-mini btn-icon"><i class="icon icon-arrow-up icon-sm"></i></button>
    <button type="button" onclick='toggleShow(this);' class="btn btn-mini btn-icon"><i class="icon icon-plus icon-sm"></i></button>
    <?php if(isset($actionFormLink)) echo common::printCommentIcon($this->moduleName);?>
  </div>
  <div class="detail-content">
    <?php if(isset($actionFormLink)):?>
      <div id='commentBox' class='hide'>
        <form method='post' action='<?php echo $actionFormLink;?>' target='hiddenwin'>
          <div class="form-group"><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
          <?php echo html::submitButton() . html::commonButton($lang->goback, "onclick=\"toggleComment('#commentBox')\"");?>
        </form>
      </div>
    <?php endif;?>
    <ol class='histories-list'>
      <?php $i = 1; ?>
      <?php foreach($actions as $action):?>
      <?php $canEditComment = (end($actions) == $action and $action->comment and $this->methodName == 'view' and $action->actor == $this->app->user->account and common::hasPriv('action', 'editComment'));?>
      <li value='<?php echo $i ++;?>'>
        <?php
        if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
        if($action->action == 'assigned' and isset($users[$action->extra]) ) $action->extra = $users[$action->extra];
        if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);
        ?>
        <span class='item'>
          <?php $this->action->printAction($action);?>
          <?php if(!empty($action->history)) echo "<button id='switchButton$i' type='button' class='btn btn-mini switch-btn btn-icon' onclick='switchChange($i)'><i class='change-show icon icon-plus icon-sm'></i></button>";?>
        </span>
        <?php if(!empty($action->comment) or !empty($action->history)):?>
        <?php if(!empty($action->comment)) echo "<div class='history'>";?>
          <div class='history-changes hide alert' id='changeBox<?php echo $i;?>'>
            <?php echo $this->action->printChanges($action->objectType, $action->history);?>
          </div>
          <?php if($canEditComment):?>
          <span class='pull-right comment<?php echo $action->id;?>'><?php echo html::commonButton('<i class="icon icon-edit icon-sm"></i>', "onclick=\"toggleComment('{$action->id}')\" style='border:0;'", 'btn btn-mini')?></span>
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
  </div>
</div>
