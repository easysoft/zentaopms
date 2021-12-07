<?php
chdir(dirname(dirname(dirname(__FILE__))));

include './framework/router.class.php';
include './framework/control.class.php';
include './framework/model.class.php';
include './framework/helper.class.php';

/**
 * Gen api data
 *
 * @param string $fileName
 * @param array  $data
 * @access public
 * @return void
 */
function genData($fileName, $data)
{
    global $app;

    $dataRoot = $app->getAppRoot() . 'db' . DS . 'api' . DS;
    $file     = fopen($dataRoot . $fileName, 'w');

    fwrite($file, serialize($data));
    fclose($file);
}

/* Gen demo data to db/api. */
$app = router::createApp('pms', dirname(dirname(dirname(__FILE__))), 'router');
$dao = $app->loadClass('dao');

$structs     = $dao->select('*')->from(TABLE_APISTRUCT)->fetchAll();;
$structSpecs = $dao->select('*')->from(TABLE_APISTRUCT_SPEC)->fetchAll();
$apis        = $dao->select('*')->from(TABLE_API)->fetchAll();
$apiSpecs    = $dao->select('*')->from(TABLE_API_SPEC)->fetchAll();
$modules     = $dao->select('*')->from(TABLE_MODULE)->where('type')->eq('api')->fetchAll();

genData('apistruct', $structs);
genData('apistruct_spec', $structSpecs);
genData('api', $apis);
genData('apispec', $apiSpecs);
genData('module', $modules);
