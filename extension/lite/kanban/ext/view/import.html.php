<?php
/**
 * The import file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     kanban
 * @version     $Id: import.html.php 935 2022-01-19 14:15:24Z hufangzhou@easycorp.ltd $
 * @link        https://www.zentao.net
 */
?>

<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php js::set('enableImport', $enableImport);?>
<?php js::set('vision', $this->config->vision);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo "<span>" . $lang->kanban->import . '</span>';?>
      </h2>
    </div>
    <form method='post' class="load-indicator main-form form-ajax" target='hiddenwin' id='importForm'>
      <table align='center' class='table table-form'>
        <tr>
          <td colspan='2'>
            <label class="radio-inline">
              <input type='radio' name='import' value='off' <?php echo $enableImport == 'off' ? "checked='checked'" : ''; ?> id='importoff'/>
              <?php echo $lang->kanban->importList['off'];?>
            </label>
          </td>
        </tr>
        <tr>
          <td colspan='2'>
            <label class="radio-inline">
              <input type='radio' name='import' value='on' <?php echo $enableImport == 'on' ? "checked='checked'" : ''; ?> id='importon'/>
              <?php echo $lang->kanban->importCards;?>
              <input type='hidden' name='importObjectList[]' value='cards'/>
            </label>
          </td>
        </tr>
        <tr id='emptyTip' class='hidden'><td colspan='3' style='color: red;'><?php echo $lang->kanban->error->importObjNotEmpty;?></td></tr>
        <tr>
          <td class='form-actions'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

