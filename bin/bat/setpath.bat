@ECHO OFF
:READ
:: SET /P prompts for input and sets the variable
:: to whatever the user types
SET path=
SET suffix=\php.exe
SET currentFile=%0
SET /P path=Input the path PHP:
::确定路径是否带有\php.exe
IF EXIST %path%%suffix% GOTO ONLYDIR
IF EXIST %path% GOTO FULLPATH
ECHO "%path%" is not valid. Please try again.
ECHO.
GOTO READ
:FULLPATH
::路径去除\php.exe
if /I %path:~-8% == %suffix% set path=%path:~0,-8%
:ONLYDIR
::完整路径
set path=%path%%suffix%
set TMPFILE=%random%.tmp
set currentFile=%currentFile:~-11%
for /r %~dp0 %%i in (*.bat) do (
	if exist %TMPFILE% (del /f/q %TMPFILE%)
::排除本文件
	if not %~dp0%currentFile%==%%i (
		echo Change %%i
		for /f "tokens=1,2* delims= " %%j in (%%i) do (
::修改文件特定的行
			if exist %%k (if "%%l"=="" (echo %path% %%k>>%TMPFILE%) else (echo %path% %%k %%l>>%TMPFILE%)) else (if "%%k"=="" (echo %%j>>%TMPFILE%)	else if "%%l"=="" (echo %%j %%k>>%TMPFILE%) else (echo %%j %%k %%l>>%TMPFILE%))
		)
		move /y %TMPFILE% "%%i"
	)
)
cd ..\
echo Change ztcli.bat
::修改ztcli.bat
if exist %TMPFILE% (del /f/q %TMPFILE%)
for /f "tokens=1,2* delims= " %%j in (ztcli.bat) do (
::修改文件特定的行
	if exist %%k (if "%%l"=="" (echo %path% %%k>>%TMPFILE%) else (echo %path% %%k %%l>>%TMPFILE%)) else (if "%%k"=="" (echo %%j>>%TMPFILE%)	else if "%%l"=="" (echo %%j %%k>>%TMPFILE%) else (echo %%j %%k %%l>>%TMPFILE%))
)
move /y %TMPFILE% "ztcli.bat"
echo Success!
pause
