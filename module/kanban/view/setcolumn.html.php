<?php
/**
 * The set column file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Thanatos<xiawenlong@easycorp.ltd>
 * @package     kanban
 * @version     $Id: setcolumn.html.php 935 2021-10-26 16:24:24Z xiawenlong@easycorp.ltd $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo "<span title='$title'>" . $title . '</span>';?>
      </h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' target='hiddenwin'>
      <table align='center' class='table table-form'>
        <tr>
          <th class='w-110px'><?php echo $lang->kanban->columnName;?></th>
          <td colspan='2'>
            <?php echo html::input('name', $column->name, "class='form-control'");?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->kanban->columnColor;?></th>
          <td colspan='3'>
            <?php echo html::hidden('color', $column->color, "class='form-control'");?>
            <ul>
            <?php foreach($config->kanban->columnColorList as $color):?>
            <li>
              <a href='javascript:setColor("<?php echo $color;?>");' class='cp-tile <?php echo $color == $column->color ? 'active' : '';?>' data-color='<?php echo $color;?>' style='color: #FFF; background: <?php echo $color;?>; border-color: transparent;'></a>
            </li>
            <?php endforeach;?>
            </ul>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
