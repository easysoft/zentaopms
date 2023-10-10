<?php
/**
 * The create ai mini program file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainContent" class="main-content" style="position: fixed; top: 70px; right: 20px; bottom: 0; left: 20px;">
    <div class="center-block" style="width: 660px;">
        <div style="display: flex; align-items: center; justify-content: flex-start;">
            <strong style="font-size: 16px;"><?php echo $lang->ai->miniPrograms->configuration; ?></strong>
            <i title="<?php echo $lang->help; ?>" class="icon icon-help text-warning" style="padding-left: 8px; padding-right: 2px;"></i>
            <span class="text-muted"><?php echo $lang->ai->miniPrograms->downloadTip; ?></span>
            <a class="text-primary" href="https://www.zentao.net/page/download-client.html" target="_blank">&gt;&gt;<?php echo $lang->ai->miniPrograms->download; ?></a>
        </div>
    </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
