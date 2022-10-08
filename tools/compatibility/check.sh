#!/usr/bin/env bash

set -eu

cd "$(dirname $0)"

php phpcs.phar  --config-set installed_paths $PWD/PHPCompatibility > /dev/null;

php ./phpcs.phar  --standard=ruleset.xml --ignore=*.css ../../module;
php ./phpcs.phar  --standard=ruleset.xml ../../framework;
php ./phpcs.phar  --standard=ruleset.xml ../../lib;
