// Copyright (C) 2007-2010 Kai Seidler, oswald@apachefriends.org, GPL-licensed

#include <windows.h>
#include <stdio.h>
#include <process.h>

#include "xampp_util.h"

int main(int argc, char **argv)
{
	char buffer[200];
	char *start[10][10] = {
				{"apache\\bin\\httpd.exe", "-f conf\\httpd.conf", NULL},
				{"mysql\\bin\\mysqld.exe", "--defaults-file=mysql\\bin\\my.ini", "--standalone",  NULL},
				{NULL}
			};

	printf("\nXAMPP now starts as a console application.\n\n");

	printf("Instead of pressing Control-C in this console window, please use xampp_stop.exe\n");
	printf("to stop XAMPP, because it lets XAMPP end any current transactions and cleanup\n");
	printf("gracefully.\n\n");


	xampp_cdx();

	xampp_call(start);
	
	Sleep(10000);


	return(0);
}
