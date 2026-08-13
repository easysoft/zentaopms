<?php
$config->misc->api          = 'https://api.zentao.net';
$config->misc->sendEventAPI = 'https://www.zentao.net/misc-event-install.html';
$config->misc->enApi        = 'https://api.zentao.pm';
$config->misc->qucheng      = 'https://www.qucheng.com';
$config->misc->zentaonet    = 'https://www.zentao.net';
$config->misc->featureLimit = '2024-06-14';

/*
 * 新功能提醒配置。后续新增提醒只需追加一项。
 * - code: 功能代号，唯一
 * - images: 图片路径列表，可用 {lang} 占位（cn/en）
 * - editions: 可选，适用版本；空表示全部
 * - linkItem: 可选，了解更多跳转 item；可为字符串或按 edition 的数组
 * - editionImages: 可选，按 edition 覆盖 images
 */
$config->featureNotice = array();
$config->featureNotice[] = array(
    'code'        => 'ui20',
    'editions'    => array('open', 'biz', 'max', 'ipd'),
    'linkItem'    => array('open' => 'release20', 'biz' => 'releasebiz10', 'max' => 'releasemax5', 'ipd' => 'releaseipd2'),
    'images'      => array(
        'static/svg/{lang}_upgrade_guide1_20_0.svg',
        'static/svg/{lang}_upgrade_guide2_20_0.svg',
        'static/svg/{lang}_upgrade_guide3_20_0.svg',
        'static/svg/{lang}_upgrade_guide4_20_0.svg'
    ),
    'editionImages' => array(
        'biz' => array(
            'static/svg/biz/{lang}_upgrade_guide1_10_0.svg',
            'static/svg/biz/{lang}_upgrade_guide2_10_0.svg',
            'static/svg/biz/{lang}_upgrade_guide3_10_0.svg',
            'static/svg/biz/{lang}_upgrade_guide4_10_0.svg'
        ),
        'max' => array(
            'static/svg/max/{lang}_upgrade_guide1_5_0.svg',
            'static/svg/max/{lang}_upgrade_guide2_5_0.svg',
            'static/svg/max/{lang}_upgrade_guide3_5_0.svg',
            'static/svg/max/{lang}_upgrade_guide4_5_0.svg'
        ),
        'ipd' => array(
            'static/svg/ipd/{lang}_upgrade_guide1_2_0.svg',
            'static/svg/ipd/{lang}_upgrade_guide2_2_0.svg',
            'static/svg/ipd/{lang}_upgrade_guide3_2_0.svg',
            'static/svg/ipd/{lang}_upgrade_guide4_2_0.svg'
        )
    )
);

$config->featureNotice[] = array(
    'code'   => 'aiskill',
    'images' => array(
        'static/png/aiskill_guide1_22.4.png',
        'static/png/aiskill_guide2_22.4.png',
        'static/png/aiskill_guide3_22.4.png'
    )
);