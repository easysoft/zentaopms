// Copyright (C) 2007-2010 Kai Seidler, oswald@apachefriends.org, GPL-licensed

#include <windows.h>
#include <stdio.h>

int main(int argc, char **argv)
{
        if(argc!=2)
        {
                printf("Usage: %s <library name>\n",argv[0]);
                exit(2);
        }

        if(LoadLibrary(argv[1]))
        {
                printf("OK\n");
                exit(0);
        }
        else
        {
                printf("NOK\n");
                exit(1);
        }



}
