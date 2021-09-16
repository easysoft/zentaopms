<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php js::set('confirmDelete', $lang->api->confirmDelete);?>
<div class="fade main-row split-row" id="mainRow">
    <?php $sideWidth = common::checkNotCN() ? '270' : '238'; ?>
    <div class="side-col" style="width:<?php echo $sideWidth; ?>px" data-min-width="<?php echo $sideWidth; ?>">
        <div class="cell" style="min-height: 286px">
            <div id='title'>
                <li class='menu-title'><?php echo $this->lang->api->apiDoc; ?></li>
                <?php
                echo "<div class='menu-actions'>";
                echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
                echo "<ul class='dropdown-menu pull-right'>";
                echo '<li>' . html::a($this->createLink('tree', 'browse', "rootID=$libID&view=api", '', true), '<i class="icon-cog-outline"></i> ' . $this->lang->api->manageType, '', "class='iframe'") . '</li>';
                echo "<li class='divider'></li>";
                echo '<li>' . html::a($this->createLink('api', 'editLib', "rootID=$libID"), '<i class="icon-edit"></i> ' . $lang->api->editLib, '', "class='iframe'") . '</li>';
                echo '<li>' . html::a($this->createLink('api', 'deleteLib', "rootID=$libID"), '<i class="icon-trash"></i> ' . $lang->api->deleteLib, 'hiddenwin') . '</li>';
                echo '</ul></div>';
                ?>
            </div>
            <?php if(!$moduleTree): ?>
                <hr class="space">
                <div class="text-center text-muted tips">
                    <?php echo $lang->api->noModule; ?>
                </div>
            <?php endif; ?>
            <?php echo $moduleTree; ?>
        </div>
    </div>

        <?php if($api):?>
            <?php include './content.html.php';?>
        <?php else: ?>
        <?php if(empty($libs) || empty($apiList)): ?>
    <div class="cell">
                <div class="detail">
                    <li class="detail-title"><?php echo intval($libID) > 0 ? $lang->api->module : $lang->api->title; ?></li>
                </div>
            <div class="detail">
                <div class="no-content"><img
                        src="<?php echo $config->webRoot . 'theme/default/images/main/no_content.png' ?>"/></div>
                <div
                    class="notice text-muted"><?php echo (empty($libs)) ? $lang->api->noLib : $lang->api->noApi; ?></div>
                <div class="no-content-button">
                    <?php
                    $html = '';
                    if($libID && common::hasPriv('api', 'create'))
                    {
                        $html = html::a(helper::createLink('api', 'create', "libID={$libID}"), '<i class="icon icon-plus"></i> ' . $lang->api->createApi, '', 'class="btn btn-info btn-wide"');
                    }
                    else
                    {
                        $html = html::a(helper::createLink('api', 'createLib'), '<i class="icon icon-plus"></i> ' . $lang->api->createLib, '', 'class="btn btn-info btn-wide iframe"');
                    }
                    echo $html;
                    ?>
                </div>
            </div>
    </div>
        <?php else: ?>

    <div class="cell">
            <div class="detail">
                <ul class="list-group">
                    <?php foreach($apiList as $api): ?>
                        <li class="list-group-item">
                            <div class="heading <?php echo $api->method;?>">
                                <a href="<?php echo helper::createLink('api', 'index', 'apiID='. $api->id) ?>">
                                    <span class="label label-primary"><?php echo $api->method; ?></span>
                                    <span
                                        class="label label-warning"><?php echo apiModel::getApiStatusText($api->status); ?></span>
                                    <span class="path"><?php echo $api->path; ?></span>
                                    <span class="desc"><?php echo $api->title; ?></span>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
    </div>
        <?php endif; ?>
        <?php endif; ?>
</div>
<?php include '../../common/view/footer.html.php'; ?>
