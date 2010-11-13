VERSION=$(shell head -n 1 VERSION)

all: tgz

clean:
	rm -fr zentaopms
	rm -fr *.tar.gz
	rm -fr *.zip
	rm -fr api*
tgz:
	mkdir -p zentaopms/lib
	mkdir -p zentaopms/db
	mkdir -p zentaopms/bin
	mkdir -p zentaopms/config
	cp -fr db zentaopms/
	cp -fr doc/* zentaopms/
	cp -fr lib/ zentaopms/
	cp -fr config/config.php zentaopms/config/
	cp -fr www zentaopms/
	cp -fr module zentaopms/
	cp bin/ztc* zentaopms/bin
	cp bin/computeburn.php zentaopms/bin
	cp bin/getbugs.php zentaopms/bin
	cp bin/initopt.php zentaopms/bin
	cp bin/todo.php zentaopms/bin
	chmod a+rx zentaopms/bin/*
	cp -fr framework zentaopms/
	cp -fr lib/* zentaopms/lib/
	find zentaopms -name .svn |xargs rm -fr
	find zentaopms -name tests |xargs rm -fr
	mkdir -p zentaopms/tmp/cache
	mkdir -p zentaopms/tmp/log
	chmod 777 -R zentaopms/tmp/
	chmod 777 zentaopms/www/data
	chmod 777 zentaopms/config
	find zentaopms -name .svn |xargs rm -fr
	rm -fr zentaopms/framework/tests
	rm -fr zentaopms/www/data/*
	rm -fr zentaopms/www/bugfree
	zip -r -9 ZenTaoPMS.$(VERSION).zip zentaopms
	rm -fr zentaopms
phpdoc:
	phpdoc -d bin,framework,config,lib,module,www -t api -o HTML:frames:phphtmllib -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
	phpdoc -d bin,framework,config,lib,module,www -t api.chm -o chm:default:default -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
doxygen:
	doxygen .doxygen
