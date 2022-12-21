<style>
.preference .table-form > tbody > tr > th {font-weight: 400; color: #0B0F18;}
.preference .chosen-container-single .chosen-single > span {color: #313C52;}
.tip {margin-top: 10px;}

.has-img > .border {
    display: flex;
    padding: 5px 16px;
    border: 1px solid #EDEEF2;
}
.has-img.picker-option-active >.border {
    border: 2px solid #2E7FFF;
}
.has-img.picker-option-active {
    background: rgba(230,240,255, 0.4)!important;
}
.has-img-text {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    padding-left: 5px;
}
.has-img-text > .title {
    color: #0B0F18;
    font-size: 13px;
}
.has-img-text > .context {
    color: #838A9D;
    font-size: 12px;
}

#pickerDropMenu-pk_URSR > .picker-option-list{
    display: flex;
    flex-wrap: wrap;
}
.option-ursr {
    flex: 1 1 200px;
}
.option-ursr.picker-option-selected > .border,
.option-ursr.picker-option-active> .border {
    border: none;
}
.option-ursr > .border  {
    height: 46px;
    display: flex;
    padding: 5px 16px;
    border: 1px solid #EDEEF2;
    align-items: center;
}
.option-ursr > .border > .value {
    margin-right: 20px;
    width: 20px;
    height: 20px;
    border-radius: 100%;
    background: rgba(230,240,255, 0.4);
}
.option-ursr > .border > .value > p { 
    line-height: 20px;
    text-align: center;
}

</style>
<div class='preference'>
<form method='post' target='hiddenwin' action='<?php echo $this->createLink('my', 'preference', "showTip=false")?>'>
    <table align='center' class='table table-form w-320px'>
      <tr>
        <th class='w-120px'><?php echo $lang->my->storyConcept;?></th>
        <td><?php echo html::select('URSR', $URSRList, $URSR, "class='form-control picker'");?></td>
      </tr>
      <?php if($this->config->systemMode == 'ALM'):?>
      <tr>
        <th><?php echo $lang->my->programLink;?></th>
        <td><?php echo html::select('programLink', $lang->my->programLinkList, $programLink, "class='form-control picker'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->my->productLink;?></th>
        <td><?php echo html::select('productLink', $lang->my->productLinkList, $productLink, "class='form-control picker'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->projectLink;?></th>
        <td><?php echo html::select('projectLink', $lang->my->projectLinkList, $projectLink, "class='form-control picker'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->executionLink;?></th>
        <td><?php echo html::select('executionLink', $lang->my->executionLinkList, $executionLink, "class='form-control picker'");?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
function initPreference() {
    var objPngSrc = {
        'program-browse': 'list',
        'program-project': 'list-recent',
        'program-kanban': 'kanban',

        'product-index': 'panel-recent-browse',
        'product-all': 'list',
        'product-dashboard': 'panel',
        'product-browse': 'list-recent',
        'product-kanban': 'kanban',

        'project-browse': 'list',
        'project-execution': 'list-recent',
        'project-index': 'panel-recent-browse',
        'project-kanban': 'kanban',

        'execution-all': 'list',
        'execution-task': 'kanban',
        'execution-executionkanban': 'list-recent',
    }
    function optionRenderProgram($option, b) {
        /* transform ， to , then split to fit lang */
        var textArr = b.text.replace(/[\uff0c]/g,",").split(',');
        $option.empty();
        $option.addClass('has-img')
        /** dom to prepend
         *  <div class="border>
         *    <div class="has-img-img"><img src="theme/default/images/guide/' + b.value + '.png"></div>
         *    <div class="has-img-text">
         *      <div class="title"></div>
         *      <div class="context"></div>
         *     </div>
         *  </div> 
         **/
        $option.prepend('<div class="border"><div class="has-img-img"><img src="theme/default/images/guide/' + objPngSrc[b.value] + '.png"></div><div class="has-img-text"><div class="title">' + textArr[0] + '</div><div class="context">' + textArr[1] + '</div></div></div>');
        return $option;
    }
    function optionRenderURSR($option, b) {
        $option.addClass('option-ursr');
        $option.parent().addClass('list-ursr');
        $option.empty();
        $option.prepend('<div class="border"><div class="value"><p>' + b.value + '</p></div><div class="context">' + b.text + '</div></div>');
        return $option;
    }
    
    $('#programLink').picker({
        optionRender: optionRenderProgram
    });
    $('#productLink').picker({
        optionRender: optionRenderProgram
    });
    $('#projectLink').picker({
        optionRender: optionRenderProgram
    });
    $('#executionLink').picker({
        optionRender: optionRenderProgram
    });
    $('#URSR').picker({
        optionRender: optionRenderURSR
    });
}

initPreference();
</script>