#replace title
foreachd(){
  for file in `ls $1`
  do 
    if [ -d $1/$file ]
    then
      echo "into dir $1/$file"
      foreachd $1/$file
    elif [ -f $1/$file ]
    then
#      sed -i '1,11s/\@license.*$/\@license     ZPL \(http:\/\/zpl\.pub\/page\/zplv11\.html\)/g' $1/$file
      sed -i '1,11s/\@copyright.*$/\@copyright   Copyright 2009-2015 禅道软件（青岛）有限公司\(QingDao Nature Easy Soft Network Technology Co,LTD, www\.cnezsoft\.com\)/g' $1/$file
    fi
  done
}

foreachd $1
