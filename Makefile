VERSION=$(shell head -n 1 VERSION)

all: prepare pms
clean:
	rm -fr {zentaopms,zentaostory,zentaotask,zentaotest,storyext,taskext,testext}
	rm -fr *.tar.gz
	rm -fr *.zip
	rm -fr api*
	rm -fr build/linux/lampp
	rm -fr lampp
prepare:
	mkdir zentaopms
	cp -fr bin zentaopms/
	cp -fr config zentaopms/ && rm -fr zentaopms/config/my.php
	cp -fr db zentaopms/
	cp -fr doc zentaopms/ && rm -fr zentaopms/doc/phpdoc && rm -fr zentaopms/doc/doxygen
	cp -fr framework zentaopms/
	cp -fr lib zentaopms/
	cp -fr module zentaopms/
	cp -fr www zentaopms && rm -fr zentaopms/www/data/ && mkdir -p zentaopms/www/data/upload
	cp -fr tmp zentaopms
	rm -fr zentaopms/tmp/cache/* 
	rm -fr zentaopms/tmp/extension/*
	rm -fr zentaopms/tmp/log/*
	rm -fr zentaopms/tmp/model/*
	cp VERSION zentaopms/
	# combine js and css files.
	cp -fr tools zentaopms/tools && cd zentaopms/tools/ && php ./minifyfront.php
	rm -fr zentaopms/tools
	# create the restart file for svn.
	# touch zentaopms/module/svn/restart
	# delee the unused files.
	find zentaopms -name .gitkeep |xargs rm -fr
	find zentaopms -name tests |xargs rm -fr
	# notify.zip.
	mkdir zentaopms/www/data/notify/
	wget http://192.168.1.99/release/notify.zip -O zentaopms/www/data/notify/notify.zip
	# change mode.
	chmod 777 -R zentaopms/tmp/
	chmod 777 -R zentaopms/www/data
	chmod 777 -R zentaopms/config
	chmod 777 zentaopms/module
	chmod a+rx zentaopms/bin/*
	find zentaopms/ -name ext |xargs chmod -R 777
pms:	
	echo full > zentaopms/.flow
	zip -r -9 ZenTaoPMS.$(VERSION).zip zentaopms
zstory:
	cp -frp zentaopms zentaostory
	svn export https://svn.cnezsoft.com/easysoft/trunk/zentaoext/zentaostory storyext
	cp -fr storyext/module/* zentaostory/module/
	find zentaostory/ -name ext |xargs chmod -R 777
	echo zentaostory > zentaostory/.flow
	sed -e 's/zentao/story/g' zentaostory/www/.ztaccess > .ztaccess.bak
	mv .ztaccess.bak zentaostory/www/.ztaccess
	zip -r -9 ZenTaoStory.$(VERSION).zip zentaostory
	rm -fr storyext
	rm -fr zentaostory
ztask:
	cp -frp zentaopms zentaotask
	svn export https://svn.cnezsoft.com/easysoft/trunk/zentaoext/zentaotask taskext
	cp -fr taskext/module/* zentaotask/module/
	find zentaotask/ -name ext |xargs chmod -R 777
	echo zentaotask > zentaotask/.flow
	sed -e 's/zentao/task/g' zentaotask/www/.ztaccess > .ztaccess.bak
	mv .ztaccess.bak zentaotask/www/.ztaccess
	zip -r -9 ZenTaoTask.$(VERSION).zip zentaotask
	rm -fr taskext
	rm -fr zentaotask
ztest:
	cp -frp zentaopms zentaotest
	svn export https://svn.cnezsoft.com/easysoft/trunk/zentaoext/zentaotest testext
	cp -fr testext/module/* zentaotest/module/
	find zentaotest/ -name ext |xargs chmod -R 777
	echo zentaotest > zentaotest/.flow
	sed -e 's/zentao/test/g' zentaotest/www/.ztaccess > .ztaccess.bak
	mv .ztaccess.bak zentaotest/www/.ztaccess
	zip -r -9 ZenTaoTest.$(VERSION).zip zentaotest
	rm -fr testext
	rm -fr zentaotest
	rm -fr zentaopms
patchphpdoc:
	sudo cp misc/doc/phpdoc/*.tpl /usr/share/php/data/PhpDocumentor/phpDocumentor/Converters/HTML/frames/templates/phphtmllib/templates/
phpdoc:
	phpdoc -d bin,framework,config,lib,module,www -t api -o HTML:frames:phphtmllib -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
	phpdoc -d bin,framework,config,lib,module,www -t api.chm -o chm:default:default -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
doxygen:
	doxygen doc/doxygen/doxygen.conf
