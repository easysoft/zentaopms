<?php
/**
 * The select lib type view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     doc
 * @version     $Id: selectlibtype.html.php 958 2021-09-3 17:09:42Z liyuchun $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo '<span>' . $lang->doc->create . '</span>';?></h2>
    </div>
  </div>
  <form method='post' class='form-ajax'>
    <table class='table table-form'>
      <tr><th class='w-120px'></th><td></td><th class='w-100px'></th><td></td><td class='w-30px'></td></tr>
      <tr>
        <th><?php echo $lang->doc->space?></th>
        <td colspan='3'><?php echo html::radio('space', $spaceList, key($spaceList), "onchange=changeSpace()");?></td>
      </tr>
      <tr id='docType'>
        <th><?php echo $lang->doc->type;?></th>
        <td colspan='3'><?php echo html::radio('type', $typeList, '', "onchange='changeDocType()'");?></td>
      </tr>
      <tr class='apiTypeTR hidden'>
        <th><?php echo $lang->doc->apiType?></th>
        <td><?php echo html::select('apiType', array('' => '') + $lang->doc->apiTypeList, '', "class='form-control picker-select' onchange=changeApiType()");?></td>
      </tr>
      <tr class='projectTR hidden'>
        <th><?php echo $lang->doc->project;?></th>
        <td class='required'><?php echo html::select('project', $projects, key($projects), "class='form-control picker-select'");?></td>
        <th class='executionTH'><?php echo $lang->doc->execution?></th>
        <td id='executionBox'><?php echo html::select('execution', array(), '', "class='form-control picker-select' onchange='loadObjectModules(\"execution\", this.value)'")?></td>
        <td class='executionHelp pl-0px'><i class='icon icon-help' title='<?php echo $lang->doc->placeholder->execution;?>'></i></td>
      </tr>
      <tr class='productTR hidden'>
        <th><?php echo $lang->doc->product;?></th>
        <td class='required'><?php echo html::select('product', $products, key($products), "class='form-control picker-select'");?></td>
      </tr>
      <tr>
        <th class='w-100px'><?php echo $lang->doc->libAndModule?></th>
        <td colspan='3' class='required'><span id='moduleBox'><?php echo html::select('module', array(), '', "class='form-control picker-select'");?></span></td>
      </tr>
      <tr>
        <td colspan='4' class='text-center form-actions'><?php echo html::submitButton($lang->doc->nextStep, 'disabled');?></td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function(){changeSpace()});

/**
 * Change space.
 *
 * @access public
 * @return void
 */
function changeSpace()
{
    var space = $('[name=space]:checked').val();
    $('.apiTypeTR').toggleClass('hidden', space != 'api');
    $('.projectTR').toggleClass('hidden', space != 'project');
    $('.productTR').toggleClass('hidden', space != 'product');
    $('#typedoc').toggleClass('hidden', space == 'api');
    $('#typeapi').toggleClass('hidden', space == 'mine' || space == 'custom');
    $('#typedoc').closest('.radio-inline').toggleClass('hidden', space == 'api');
    $('#typeapi').closest('.radio-inline').toggleClass('hidden', space == 'mine' || space == 'custom');
    $('#docType').toggleClass('hidden', $('#docType [name=type]:not(.hidden)').length == 1);

    var docType = $('[name=type]:not(.hidden):checked').val();
    if(space == 'project' && docType) $('#project').change();
    if(space == 'product' && docType) $('#product').change();
    if((space == 'mine' || space == 'custom') && docType) loadDocLibs(space, docType);
    if(space == 'api' && docType) changeApiType();

    if(!docType)
    {
        $('[name=type]:not(.hidden):first').prop('checked', true);
        changeDocType();
    }
}

/**
 * Change api type.
 *
 * @access public
 * @return void
 */
function changeApiType()
{
    var apiType = $('#apiType').val();
    $('.projectTR').toggleClass('hidden', apiType != 'project');
    $('.productTR').toggleClass('hidden', apiType != 'product');
    if(apiType == 'project') $('#project').change();
    if(apiType == 'product') $('#product').change();
    if(apiType == 'nolink')  loadDocLibs('api', 'api');
    if(apiType == '')
    {
        $('#moduleBox').html("<select id='module' name='module' class='form-control'><option value=''></option></select>");
        $('#moduleBox #module').picker();
    }
}

/**
 * Change doc type.
 *
 * @access public
 * @return void
 */
function changeDocType()
{
    var docType = $('[name=type]:not(.hidden):checked').val();
    $('.executionTH').removeClass('hidden');
    $('.executionHelp').removeClass('hidden');
    $('#executionBox').removeClass('hidden');
    if(docType == 'api')
    {
        $('.executionTH').addClass('hidden');
        $('.executionHelp').addClass('hidden');
        $('#executionBox').addClass('hidden');
        $('#project').attr('onchange', "loadObjectModules('project', this.value, '" + docType + "')");
    }
    else if(docType == 'doc')
    {
        $('#project').attr('onchange', "loadExecutions(this.value)");
    }
    $('#product').attr('onchange', "loadObjectModules('product', this.value, '" + docType + "')");

    var space = $('[name=space]:checked').val();
    if(space) changeSpace();

    $('#submit').removeAttr('disabled');
}
</script>
<?php include '../../common/view/footer.lite.html.php';?>
