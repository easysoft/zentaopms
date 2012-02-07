all: checkdll.exe xampp_start.exe xampp_stop.exe xamppcli.exe

clean:
	rm checkdll.exe xampp_start.exe xampp_stop.exe wrapper.exe xamppcli.exe
	rm *.obj

checkdll.exe: checkdll.c
	cl /nologo checkdll.c
	cp checkdll.exe /c/XAMPP/bin

xampp_util.obj: xampp_util.c
	cl /nologo /c xampp_util.c

xampp.res: xampp.rc
	rc xampp.rc

xampp_start.exe: xampp_start.c xampp_util.obj xampp_util.h xampp.res
	cl /nologo xampp_start.c xampp_util.obj xampp.res
	cp xampp_start.exe /c/XAMPP/start.exe

xampp_stop.exe: xampp_stop.c xampp_util.obj xampp_util.h xampp.res
	cl /nologo xampp_stop.c xampp_util.obj xampp.res
	cp xampp_stop.exe /c/XAMPP/stop.exe

xamppcli.exe: xamppcli.c xampp_util.obj xampp_util.h xampp.res
	cl /nologo xamppcli.c xampp_util.obj xampp.res
	cp xamppcli.exe /c/XAMPP/bin

