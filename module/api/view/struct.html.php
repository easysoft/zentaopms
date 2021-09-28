<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Thanatos <thanatos915@163.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php js::set('libID', $libID)?>
<?php js::set('structID', $structID)?>
<div class="fade main-row split-row" id="mainRow">
  <?php if($libID): ?>
    <?php $sideWidth = common::checkNotCN() ? '270' : '238'; ?>
    <div class="side-col" style="width:<?php echo $sideWidth; ?>px" data-min-width="<?php echo $sideWidth; ?>">
      <div class="cell" style="min-height: 286px">
        <div id='title'>
          <li class='menu-title'>
            <?php echo $structID ? $this->lang->api->editStruct : $this->lang->api->struct; ?>
          </li>
        </div>
        <hr class="space">
        <?php echo $tree; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="main-col" data-min-width="400">
    <div id="mainContent" class="main-content in">
      <div class='center-block'>
        <div class='main-header'>
          <h2><?php echo $structID ? $this->lang->api->editStruct : $this->lang->api->createStruct; ?></h2>
        </div>
        <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->api->structName ?></th>
              <td style="width: 80%"><?php echo html::input('name', $struct ? $struct->name : '', "class='form-control'") ?></td>
            </tr>
            <tr>
              <th><?php echo $lang->api->structAttr ?></th>
              <td colspan="2" id="paramDiv">
                <?php
                $params = array();
                if($struct && $struct->attribute)
                  foreach($struct->attribute as $item)
                    array_push($params, $item);
                else
                  array_push($params, '');
                ?>
                <?php foreach($params as $key => $param): ?>
                <div class="row row-no-gutters col-attr">
                  <div class='col-md-10'>
                    <div class="table-row input-group">
                      <span class='input-group-addon w-50px'><?php echo $lang->struct->field; ?></span>
                      <?php echo html::input("attribute[$key][field]", $param ? $param['field'] : '', "class='form-control'"); ?>
                      <span class='input-group-addon w-50px'><?php echo $lang->struct->paramsType; ?></span>
                      <?php echo html::select("attribute[$key][paramsType]", $lang->api->structParamsOptons, $param ? $param['paramsType'] : '', "class='form-control paramsType' onchange='changeType(this);'"); ?>
                      <div class="ref" style="display: none">
                        <span class='input-group-addon w-70px'><?php echo $lang->api->struct; ?></span>
                        <?php echo html::select("attribute[$key][ref]", '', $param ? $param['ref'] : '', "class='form-control'"); ?>
                      </div>
                      <span class='input-group-addon w-50px'><?php echo $lang->struct->desc; ?></span>
                      <?php echo html::input("attribute[$key][desc]", $param ? $param['desc'] : '', "class='form-control'"); ?>
                    </div>
                  </div>
                  <div class='col-md-2 '>
                    <span class='input-group-addon w-40px'><a onclick='addItem(this);'><i class='icon icon-plus'></i></a></span>
                    <span class='input-group-addon w-40px'><a onclick='deleteItem(this)'><i class='icon icon-close'></i></a></span>
                  </div>
                </div>
                <?php endforeach; ?>
              </td>
            </tr>
            <tr>
              <td colspan='3' class='text-center form-actions'>
                <?php echo html::submitButton(); ?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
