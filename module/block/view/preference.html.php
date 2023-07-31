<style>
.preference .table-form > tbody > tr > th {font-weight: 400; color: #0B0F18;}
.preference .chosen-container-single .chosen-single > span {color: #313C52;}
.tip {margin-top: 10px;}

.preference > .preference-border {
    display: flex;
    padding: 5px 16px;
    border: 1px solid #EDEEF2;
}
.preference-border > .preference-img {
    flex: 0 0 80px;
}
.preference.picker-option-active >.preference-border {
    border: 2px solid #2E7FFF;
}
.preference.picker-option-active {
    background: unset!important;
}
.preference.picker-option-active > .preference-border {
    background: rgba(230,240,255, 0.4)!important;
}
.picker-option.picker-option-selected.preference {
    background: unset!important;
}
.picker-option.picker-option-selected.preference > .preference-border {
    background: rgba(230,240,255, 0.4)!important;
}
.preference-text {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    padding-left: 5px;
}
.preference-text > .title {
    color: #0B0F18;
    font-size: 13px;
}
.preference-text > .context {
    color: #838A9D;
    font-size: 12px;
    overflow: hidden;
    white-space: normal;
}

.picker-option.picker-option-active,
.picker-single .picker-option.picker-option-selected.picker-option-active.option-ursr,
.picker-option.picker-option-selected.option-ursr {
    background: unset!important;
    color: unset;
}
.picker-option.picker-option-active > .border,
.picker-single .picker-option.picker-option-selected.picker-option-active.option-ursr > .border,
.picker-single .picker-option.picker-option-selected.picker-option-active.option-ursr > .border {
    background: rgba(230,240,255, 0.4)!important;
}
#pickerDropMenu-pk_URSR > .picker-option-list {
    display: flex;
    flex-wrap: wrap;
}
.option-ursr {
    flex: 1 1 50%;
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
        <td><?php echo html::select('URSR', $URSRList, $URSR, "class='form-control picker URSR'");?></td>
      </tr>
      <?php if(in_array($config->systemMode, array('ALM', 'PLM'))):?>
      <tr>
        <th><?php echo $lang->my->programLink;?></th>
        <td><?php echo html::select('programLink', $lang->my->programLinkList, $programLink, "class='form-control picker programLink'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->my->productLink;?></th>
        <td><?php echo html::select('productLink', $lang->my->productLinkList, $productLink, "class='form-control picker productLink'");?></td>
      </tr>
      <?php if($config->vision != 'or'):?>
      <tr>
        <th><?php echo $lang->my->projectLink;?></th>
        <td><?php echo html::select('projectLink', $lang->my->projectLinkList, $projectLink, "class='form-control picker projectLink'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->executionLink;?></th>
        <td><?php echo html::select('executionLink', $lang->my->executionLinkList, $executionLink, "class='form-control picker executionLink'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
/**
 * Init preference.
 *
 * @access public
 * @return void
 */
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
        'execution-task': 'list-recent',
        'execution-executionkanban': 'kanban',
    }

    /**
     * Render program option.
     *
     * @param  object  $option
     * @param  object  $b
     * @access public
     * @return object
     */
    function optionRenderProgram($option, b)
    {
        /* transform ， to , then split to fit lang */
        var textArr = b.text.split('/');
        if (!$option.hasClass('preference'))
        {
            $option.empty();
            $option.addClass('preference');
            $option.attr("title", textArr[0]);
            $option.prepend('<div class="preference-border"><div class="preference-img"><img src="theme/default/images/guide/' + objPngSrc[b.value] + '.png"></div><div class="preference-text"><div class="title">' + textArr[0] + '</div><div class="context">' + textArr[1] + '</div></div></div>');
        }
        return $option;
    }

    /**
     * Render program text.
     *
     * @param  object  $text
     * @param  object  $b
     * @access public
     * @return object
     */
    function textRenderProgram($text, b)
    {
        $text.empty();
        $text.addClass('preference-selection');
        $text.prepend('<span>' + b.split('/')[0] + '</span>')
        return $text;
    }

    /**
     * Render URSR option.
     *
     * @param  object  $option
     * @param  object  $b
     * @access public
     * @return object
     */
    function optionRenderURSR($option, b)
    {
        if (!$option.hasClass('option-ursr'))
        {
            $option.addClass('option-ursr');
            $option.parent().addClass('list-ursr');
            $option.empty();
            $option.prepend('<div class="border shadow-primary-hover"><div class="value"><p>' + (b.$_index + 1) + '</p></div><div class="context">' + b.text + '</div></div>');
        }
        return $option;
    }

    $('.programLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.productLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.projectLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.executionLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.URSR').picker({
        optionRender: optionRenderURSR
    });

    $(document).on('mousemove', 'a.picker-option', function(e)
    {
        $('.picker-option.text-primary').removeClass('text-primary');
        $(this).addClass('text-primary');
    });
}

$(function()
{
    initPreference();
})

</script>

