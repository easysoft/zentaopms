<?php
/**
 * The createByg view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2014 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form action='<?php echo $this->createLink('bug', 'create', "product=$productID&extras=$extras")?>' target='_parent' method='post'>
  <table class='table table-condensed table-hover table-striped tablesorter table-fixed'>
    <thead>
      <tr>
        <th class='w-60px'><?php echo $lang->testcase->stepID;?></th>
        <th class='w-p40'><?php echo $lang->testcase->stepDesc;?></th>
        <th class='w-p20'><?php echo $lang->testcase->stepExpect;?></th>
        <th><?php echo $lang->testcase->result;?></th>
        <th class='w-p20'><?php echo $lang->testcase->real;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1;?>
      <?php foreach($result->stepResults as $stepID => $stepResult):?>
      <tr>
        <td>
          <input type='checkbox' name='stepIDList[]'  value='<?php echo $stepID;?>'/>
          <?php echo $i?>
        </td>
        <td><?php echo $stepResult['desc']?></td>
        <td><?php echo $stepResult['expect']?></td>
        <td class='<?php echo zget($stepResult, 'result');?> text-center'><?php echo zget($lang->testcase->resultList, zget($stepResult, 'result'));?></td>
        <td><?php echo zget($stepResult, 'real');?></td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='5'>
          <?php
          echo "<div class='btn-group'>" . html::selectButton() . '</div>';
          echo html::submitButton();
          ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
