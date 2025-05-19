<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(!empty($blockHistory)):?>
<div class="panel block-histories histories no-margin" data-textDiff="<?php echo $lang->action->textDiff;?>" data-original="<?php echo $lang->action->original;?>">
<?php else:?>
<div class="detail histories" id='actionbox' data-textDiff="<?php echo $lang->action->textDiff;?>" data-original="<?php echo $lang->action->original;?>">
<?php endif;?>
<style>
.histories-list > li {word-break: break-word; word-wrap: break-word;}
.history-changes del {padding-left: 3px;}
</style>
  <script>
  $(function()
  {
      var diffButton = '<button type="button" class="btn btn-mini btn-icon btn-strip"><i class="icon icon-code icon-sm"></i></button>';
      var newBoxID = '';
      var oldBoxID = '';
      $('blockquote.textdiff').each(function()
      {
          newBoxID = $(this).parent().attr('id');
          if(newBoxID != oldBoxID)
          {
              oldBoxID = newBoxID;
              if($(this).html() != $(this).next().html()) $(this).closest('.history-changes').before(diffButton);
          }
      });
  });
  </script>
  <?php if(!empty($blockHistory)):?>
  <div class="panel-heading"><div class="panel-title">
  <?php else:?>
  <div class="detail-title">
  <?php endif;?>
    <?php echo $lang->history?> &nbsp;
    <button type="button" class="btn btn-mini btn-icon btn-reverse" title='<?php echo $lang->reverse;?>'>
      <i class="icon icon-arrow-up icon-sm"></i>
    </button>
    <button type="button" class="btn btn-mini btn-icon btn-expand-all" title='<?php echo $lang->switchDisplay;?>'>
      <i class="icon icon-plus icon-sm"></i>
    </button>
    <?php if(isset($actionFormLink)) echo common::printCommentIcon($actionFormLink);?>
  </div>
  <?php if(!empty($blockHistory)):?>
  </div>
  <?php endif;?>
  <?php if(!empty($blockHistory)):?>
  <div class="panel-body">
  <?php else:?>
  <div class="detail-content">
  <?php endif;?>
    <ol class='histories-list'>
      <?php $i = 1; ?>
      <?php foreach($actions as $action):?>
      <?php $canEditComment = ((!isset($canBeChanged) or !empty($canBeChanged)) and end($actions) == $action and trim($action->comment) != '' and strpos(',view,objectlibs,viewcard,', ",$this->methodName,") !== false and $action->actor == $this->app->user->account and common::hasPriv('action', 'editComment'));?>
      <li value='<?php echo $i ++;?>'>
        <?php
        $action->actor = zget($users, $action->actor);
        if($action->action == 'assigned' or $action->action == 'toaudit') $action->extra = zget($users, $action->extra);
        if(strpos($action->actor, ':') !== false) { $action->actor = substr($action->actor, strpos($action->actor, ':') + 1); }
        $this->loadModel('action')->printAction($action);
        ?>
        <?php if(!empty($action->history)):?>
          <button type='button' class='btn btn-mini switch-btn btn-icon btn-expand' title='<?php echo $lang->switchDisplay;?>'><i class='change-show icon icon-plus icon-sm'></i></button>
          <div class='history-changes' id='changeBox<?php echo $i;?>'>
            <?php echo $this->action->printChanges($action->objectType, $action->objectID, $action->history);?>
          </div>
        <?php endif;?>
        <?php if(strlen(trim(($action->comment))) != 0):?>
          <?php if($canEditComment):?>
            <?php echo html::commonButton('<i class="icon icon-pencil"></i>', "title='{$lang->action->editComment}'", 'btn btn-link btn-icon btn-sm btn-edit-comment');?>
            <style>.comment .comment-content{width: 98%}</style>
          <?php endif;?>
          <div class='article-content comment'>
            <div class='comment-content'>
              <?php
              if(strpos($action->comment, '<pre class="prettyprint lang-html">') !== false)
              {
                  $before   = explode('<pre class="prettyprint lang-html">', $action->comment);
                  $after    = explode('</pre>', $before[1]);
                  $htmlCode = $after[0];
                  $text     = $before[0] . htmlspecialchars($htmlCode) . $after[1];
                  echo $text;
              }
              else
              {
                  echo strip_tags($action->comment) == $action->comment ? nl2br($action->comment) : $action->comment;
              }
              ?>
            </div>
          </div>
          <?php if($canEditComment):?>
            <form method='post' class='comment-edit-form' action='<?php echo $this->createLink('action', 'editComment', "actionID=$action->id")?>'>
              <div class="form-group">
              <?php echo html::textarea('lastComment', htmlSpecialString($action->comment), "rows='8' autofocus='autofocus'");?>
              </div>
              <div class="form-group form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::commonButton($lang->close, '', 'btn btn-wide btn-hide-form');?>
              </div>
            </form>
          <?php endif;?>
        <?php endif;?>
      </li>
      <?php endforeach;?>
    </ol>
  </div>
</div>
