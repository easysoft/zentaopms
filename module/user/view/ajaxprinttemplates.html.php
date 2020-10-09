<?php if(empty($link)):?>
<?php
foreach($templates as $key => $template)
{
    echo "<li id='tplBox$template->id' onmouseover='displayXIcon($template->id)' onmouseout='hideXIcon($template->id)'>";
    echo "<a title='{$lang->user->applyTemplate}' class='tpl-name' id='tplTitleBox$template->id' href='javascript:setTemplate($template->id)'>";
    if($template->public) echo "<span class='label label-info label-badge'>{$lang->public}</span> ";
    echo $template->title . "</a>";
    if(empty($template->public) or $template->account == $app->user->account or $app->user->admin) echo "<a href='###' onclick='deleteTemplate($template->id)' id='templateID$template->id' class='btn-delete hidden'><i class='icon-close'></i></a>";
    echo "<span id='template$template->id' class='hidden'>$template->content</span>";
    echo '</li>';
}
?>
<?php else:?>
<style>
#tplBoxWrapper {position: relative; z-index: 10;}
#tplBoxWrapper > .btn-toolbar {position: absolute; right: 1px; top: 1px;}
#tplBoxWrapper .btn {padding: 4px 8px; border-top:0px; border-bottom:0px;}
#tplBoxWrapper #applyTplBtn {border-right:0px;}
#tplBox li {position: relative;}
#tplBox li .btn-delete {position: absolute; right: 0; top: -5px; display: block; width: 40px; text-align:center;}
#tplBox li:hover .btn-delete {color:#fff;}
#tplBox li .tpl-name {padding-right: 40px;}
</style>
<div id='tplBoxWrapper'>
  <div class='btn-toolbar'>
    <div class='btn-group'>
      <button id='saveTplBtn' type='button' class='btn btn-mini' data-toggle='saveTplModal'><?php echo $lang->user->saveTemplate?></button>
      <button id='applyTplBtn' type='button' class='btn btn-mini dropdown-toggle' data-toggle='dropdown'><?php echo $lang->user->applyTemplate?> <span class='caret'></span></button>
      <ul id='tplBox' class='dropdown-menu pull-right'>
        <?php
        foreach($templates as $key => $template)
        {
            echo "<li id='tplBox$template->id' onmouseover='displayXIcon($template->id)' onmouseout='hideXIcon($template->id)'>";
            echo "<a title='{$lang->user->applyTemplate}' class='tpl-name' id='tplTitleBox$template->id' href='javascript:setTemplate($template->id)'>";
            if($template->public) echo "<span class='label label-info label-badge'>{$lang->public}</span> ";
            echo $template->title . "</a>";
            if(empty($template->public) or $template->account == $app->user->account or $app->user->admin) echo "<a href='###' onclick='deleteTemplate($template->id)' id='templateID$template->id' class='btn-delete hidden'><i class='icon-close'></i></a>";
            echo "<span id='template$template->id' class='hidden'>$template->content</span>";
            echo '</li>';
        }
        ?>
      </ul>
    </div>
  </div>
</div>
<div class="modal fade" id="saveTplModal" tabindex="-1" role="dialog">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->user->setTemplateTitle;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <input type="text" id="title" value="" class="form-control" autocomplete="off">
          <?php if(common::hasPriv('user', 'setPublicTemplate')):?>
          <span class="input-group-addon">
            <div class="checkbox-primary">
              <input type="checkbox" value="1" id="public" />
              <label for="public"><?php echo $lang->public;?></label>
            </div>
          </span>
          <?php endif;?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->save, "id='templateSubmit'", 'btn btn-primary')?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function setTemplate(templateID)
{
    $('#tplBox .list-group-item.active').removeClass('active');
    $('#tplTitleBox' + templateID).closest('.list-group-item').addClass('active');
    var content = $('#template' + templateID).html();
    var cmd     = editor['<?php echo $link;?>'].edit.cmd;
    editor['<?php echo $link;?>'].html('');
    cmd.inserthtml(content);
}

function deleteTemplate(templateID)
{
    if(!templateID) return;
    if(confirm(<?php echo json_encode($lang->user->confirmDeleteTemplate);?>))
    {
        hiddenwin.location.href = createLink('user', 'ajaxDeleteTemplate', 'templateID=' + templateID);
        $('#tplBox' + templateID).addClass('hidden');
    }
}

function displayXIcon(templateID)
{
    $('#templateID' + templateID).removeClass('hidden');
}

function hideXIcon(templateID)
{
    $('#templateID' + templateID).addClass('hidden');
}

$(function()
{
    $('#saveTplModal').on('hide.zui.modal', function(){$(this).find('#title').val('');});
    $('#saveTplBtn').click(function()
    {
        var content = editor['<?php echo $link;?>'].html();
        if(!content)
        {
            bootAlert("<?php echo $lang->user->tplContentNotEmpty ?>");
            return;
        }
        $('#saveTplModal').modal('show');
    });
    $('#saveTplModal #templateSubmit').click(function()
    {
        var $inputGroup = $('#saveTplModal div.input-group');
        var $publicBox  = $inputGroup.find('input#public');
        var title       = $inputGroup.find('#title').val();
        var content     = editor['<?php echo $link;?>'].html();
        var isPublic    = ($publicBox.size() > 0 && $publicBox.prop('checked')) ? $publicBox.val() : 0;
        if(!title || !content) return;
        saveTemplateLink = <?php echo json_encode($this->createLink('user', 'ajaxSaveTemplate', "type=$type"));?>;
        $.post(saveTemplateLink, {title:title, content:content, public:isPublic}, function(data)
        {
            $('#tplBox').html(data);
            // If has error then not hide.
            if(data.indexOf('alert') == -1) $('#saveTplModal').modal('hide');
        });
    });
})
</script>
<?php endif;?>
