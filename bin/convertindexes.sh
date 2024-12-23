#!/bin/bash

rootDir=$(dirname $(dirname $(realpath $0)))
input_file="$rootDir/db/zentao.sql"
output_file="$rootDir/config/zentaouniqueindexes.php"

if [ -f $output_file ]; then
    rm $output_file
fi

# 提取索引并转换为PHP数组格式,通过管道排序,添加逗号分隔符,并删除空行
(
echo "<?php"
echo "\$config->indexes = ["
grep -oP 'CREATE UNIQUE INDEX[^;]+;' $input_file | while read -r line; do
    table=$(echo $line | grep -oP 'ON `\K[^`]+' | sed "s/zt_/TABLE_/" | tr '[:lower:]' '[:upper:]')
    array_items=$(echo $line | grep -oP '\(\K[^)]+' | sed 's/`/'"'"'/g' | sed 's/,/, /g')
    echo "    $table => [$array_items]"
done | sort | sed '$!s/$/,/'
echo "];"
) | tr -s '\n' > $output_file
