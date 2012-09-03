VERSION=$(shell head -n 1 VERSION)

all: zip

clean:
	rm -fr syncer
	rm -fr *.zip
zip:
	mkdir -p syncer/
	cp -fr ../../../lib/api/api.class.php syncer/
	cp -fr config.php syncer/
	cp -fr syncer.php syncer/
	zip -r -9 svnsyncer.$(VERSION).zip syncer
	rm -fr syncer
