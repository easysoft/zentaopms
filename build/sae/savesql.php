function saveSQL4SAE()
{
    if(!class_exists('dao')) return;
    global $app;
    $log = date('Ymd H:i:s') . ": " . $app->getURI() . "\n";
    foreach(dao::$querys as $query) $log .= $query . "\n";
    sae_debug($log);
}
