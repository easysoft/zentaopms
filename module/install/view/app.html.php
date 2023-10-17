<?php
/**
 * The app view file of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     install
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('notices', $lang->solution->notices);?>
<?php js::set('errors', $lang->solution->errors);?>
<?php js::set('cloudSolutionID', $cloudSolution->id);?>
<?php js::set('freeMemory', $freeMemory);?>
<?php js::set('appMap', $appMap);?>
<?php js::set('category', $category);?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <h3><?php echo $lang->install->solution->title;?></h3>
    </div>
    <div class='modal-body'>
      <form id='installForm' style="width: 600px;margin: auto;" method='post' class='form <?php if(empty($step2)) echo 'form-ajax';?> not-watch'>
        <table class='table table-form'>
          <tbody>
            <tr>
              <th></th>
              <td><?php echo $lang->install->solution->desc;?></td>
            </tr>
            <?php
              foreach($components->category as $item):
              if(in_array($item->name, array('analysis', 'artifact'))) array_unshift($item->choices, (object)array('name' => $lang->install->solution->skipInstall, 'version' => ''));
              if($item->name === 'pms') continue;
            ?>
            <tr>
              <th><?php echo $item->alias;?></th>
              <td><?php echo html::select($item->name, $this->solution->createSelectOptions($item->choices, $cloudSolution), '', "class='form-control'");?></td>
            </tr>
            <?php endforeach;?>
            <tr></tr>
            <tr>
              <th></th>
              <td class="hide" id="overMemoryNotice"><?php echo $lang->install->solution->overMemory;?></td>
            </tr>
          </tbody>
        </table>
        <div class='text-center form-actions'>
          <?php echo html::a(inlink('step6'), $lang->install->solution->skip, '', "id='skipBtn' class='btn btn-install btn-wide' style='display:none;'");?>
          <?php echo html::commonButton($lang->solution->install, "id='submitBtn' disabled=disabled", 'btn btn-primary btn-wide')?>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../../common/view/footer.lite.html.php';?>