<?php
/**
 * The setlaneheight file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: setlaneheight.html.php 935 2022-01-11 10:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.table-form>tbody>tr>th {width: 90px;}
.table-form>tbody {height:130px}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->setLaneHeight;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th id='c-name'><?php echo $lang->kanban->laneHeight;?></th>
          <td><?php echo nl2br(html::radio('heightType', $lang->kanbanlane->heightTypeList, $heightType, "onclick='setCardCount(this.value);'"));?></td>
        </tr>
        <tr class="hidden" id='cardBox'>
          <th class='c-count'><?php echo $lang->kanban->cardCount;?></th>
          <td><?php echo html::input('displayCards', $displayCards, "class='form-control' required placeholder='{$lang->kanban->cardCountTip}'  autocomplete='off'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
