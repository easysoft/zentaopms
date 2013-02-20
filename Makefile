VERSION=$(shell head -n 1 VERSION)

all: tgz
linux: tgz build4linux

clean:
	rm -fr zentaopms
	rm -fr *.tar.gz
	rm -fr *.zip
	rm -fr api*
	rm -fr build/linux/lampp
	rm -fr lampp
tgz:
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
	mkdir -p zentaopms/build/tools && cp build/tools/minifyfront.php zentaopms/build/tools/
	cd zentaopms/build/tools/ && php ./minifyfront.php
	rm -fr zentaopms/build
	# create the restart file for svn.
	# touch zentaopms/module/svn/restart
	# delee the unused files.
	find zentaopms -name .svn |xargs rm -fr
	find zentaopms -name tests |xargs rm -fr
	# change mode.
	chmod 777 -R zentaopms/tmp/
	chmod 777 -R zentaopms/www/data
	chmod 777 -R zentaopms/config
	chmod 777 zentaopms/module
	chmod a+rx zentaopms/bin/*
	find zentaopms/ -name ext |xargs chmod -R 777
	# add zentaotest zentaotask zentaostory extension.
	svn export https://svn.cnezsoft.com/easysoft/trunk/zentaoext/zentaotest
	svn export https://svn.cnezsoft.com/easysoft/trunk/zentaoext/zentaotask
	svn export https://svn.cnezsoft.com/easysoft/trunk/zentaoext/zentaostory
	zip -rm -9 zentaotest.zip zentaotest
	zip -rm -9 zentaotask.zip zentaotask
	zip -rm -9 zentaostory.zip zentaostory
	mv zentaotest.zip zentaopms/tmp/extension
	mv zentaostory.zip zentaopms/tmp/extension
	mv zentaotask.zip zentaopms/tmp/extension
	# notify.zip.
	mkdir zentaopms/www/data/notify/
	wget http://192.168.1.99/release/notify.zip -O zentaopms/www/data/notify/notify.zip
	# zip it.
	zip -r -9 ZenTaoPMS.$(VERSION).zip zentaopms
	rm -fr zentaopms
patchphpdoc:
	sudo cp misc/doc/phpdoc/*.tpl /usr/share/php/data/PhpDocumentor/phpDocumentor/Converters/HTML/frames/templates/phphtmllib/templates/
phpdoc:
	phpdoc -d bin,framework,config,lib,module,www -t api -o HTML:frames:phphtmllib -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
	phpdoc -d bin,framework,config,lib,module,www -t api.chm -o chm:default:default -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
doxygen:
	doxygen doc/doxygen/doxygen.conf
build4linux:	
	unzip ZenTaoPMS.$(VERSION).zip
	rm -fr ZenTaoPMS.$(VERSION).zip
	# build xmapp.
	cd ./build/linux/ && ./buildxmapp.sh $(xampp)
	mv ./build/linux/lampp ./
saas:	
	mkdir backup
	mkdir tmp/model
	mkdir tmp/extension
	mkdir www/data/upload -p
	chmod 777 backup
	chmod 777 -R tmp
	chmod 777 -R www/data
