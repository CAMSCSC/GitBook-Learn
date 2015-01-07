/*Example 6*/
#include <string.h>
#include <stdlib.h>
#include <stdio.h>

void main(int argc, char *argv[])
{
	if(argc != 2)
	{
		exit(0);
	}	
	char buffer[64];
	strcpy(buffer, argv[1]);
}