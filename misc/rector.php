<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Misc\Rector\RemoveReturnType;
use Misc\Rector\DowngradeParameterType;
use Misc\Rector\RemoveFunctionType;

$version = getenv('PHP_VERSION');
$version = $version ?: '72';
$cacheDir = getenv('RECTOR_CACHE_DIR');
$cacheDir = $cacheDir ?: '/tmp/rector_cached_files';

return static function (RectorConfig $rectorConfig) use ($version, $cacheDir): void {
    $rectorConfig->skip([
        'test/*',
        'framework/zand',
    ]);
    $rectorConfig->phpVersion(constant(PhpVersion::class . '::PHP_' . $version));
    $rectorConfig->sets([constant(DowngradeLevelSetList::class . '::DOWN_TO_PHP_' . $version)]);
    $rectorConfig->rules([
        RemoveReturnType::class,
        RemoveFunctionType::class,
        DowngradeParameterType::class
    ]);
    // $rectorConfig->sets([constant(DowngradeSetList::class . '::PHP_' . $version)]);
    $rectorConfig->cacheClass(FileCacheStorage::class);
    $rectorConfig->cacheDirectory($cacheDir);
};
