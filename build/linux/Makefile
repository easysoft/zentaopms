VERSION=$(shell head -n 1 zentao/VERSION)

all: 7z
7z:
	rm -fr logs/*
	rm -fr var/mysql/*.err
	rm -fr var/mysql/ib*
	mkdir .package
	mv * .package
	mv .package lampp
	mv lampp/Makefile .
	7z a -sfx ZenTaoPMS.${VERSION}.linux.7z lampp
clean:
	mv lampp/* .
	rm -fr *.7z
	rm -fr lampp
