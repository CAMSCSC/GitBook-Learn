/*Example 5*/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

void lonely(void)
{
	printf("Better secret!!\n");
}

int func(int auth, char *str)
{
	char data[32];
	strcpy(data, str);
	if(auth == 1)
	{
		printf("Secret!\n");
	}
	else
	{
		exit(0);
	}
}

int main(int argc, char *argv[])
{
	if(argc != 2)
	{
		exit(0);
	}
	func(0, argv[1]); 
}