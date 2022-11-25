<?php
/**
 * The select lib type view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.zentao.net)
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
      <tr>
        <th class='w-80px'><?php echo $lang->doc->libType;?></th>
        <td class='w-p90'><?php echo html::radio('objectType', $globalTypeList, $defaultType, "onchange=loadDocLibs(this.value)");?></td>
      </tr>
      <tr>
        <th class='w-100px'><?php echo $lang->doc->lib;?></th>
        <td class='w-p90'><?php echo html::select('lib', $libs, '', "class='form-control chosen'");?></td>
      </tr>
      <tr id='docType'>
        <th><?php echo $lang->doc->type;?></th>
        <?php
        $typeKeyList = array();
        foreach($lang->doc->types as $typeKey => $typeName) $typeKeyList[$typeKey] = $typeKey;
        ?>
        <td><?php echo html::radio('type', $lang->doc->types, 'text');?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'><?php echo html::submitButton($lang->doc->nextStep);?></td>
      </tr>
    </table>
  </form>
</div>
<?php js::set('defaultType', $defaultType);?>
<?php include '../../common/view/footer.lite.html.php';?>
