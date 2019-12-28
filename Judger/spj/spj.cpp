#include <stdio.h>
#include <iostream>
#include <string>
#include <fstream>
#include <string.h>

using namespace std;

enum ProgramState
{
	WATING = 0,
	PENDING,
	COMPILING,
	RUNNING,
	CORRECT,
	PRESENTATION_ERROR,
	TIMELIMIT,
	MEMORYLIMIT,
	WRONG_ANSWER,
	RUN_ERROR,
	OUTPUTLIMIT,
	COMPILE_ERROR,
	SYSTEM_ERROR
};

//删除首末空格
void RemoveStringBlank(string &Str)
{
	if (!Str.empty())
	{
		Str.erase(0, Str.find_first_not_of(" "));
		Str.erase(Str.find_last_not_of(" ") + 1);
	}
}

//PE结果评测
bool PresentationErrorTest(const char* program, const char* tester)
{
	ifstream is1(program);
	ifstream is2(tester);

	char buf1[1000];
	char buf2[1000];

	bool Res = true;

	while (is2 >> buf2)
	{
		if (!(is1 >> buf1))
		{
			Res = false;
			break;
		}

		if (strcmp(buf1, buf2) != 0)
		{
			Res = false;
			break;
		}
	}

	if (is1 >> buf1)
	{
		Res = false;
	}

	is1.close();
	is2.close();

	return Res;
}

//输出超限评测
bool OutputLimitExceededTest(const char* program, const char* tester)
{
	ifstream is1(program);
	ifstream is2(tester);

	char buf1[1000];
	char buf2[1000];

	bool Res = true;

	while (is1 >> buf1)
	{
		if (!(is2 >> buf2))
		{
			Res = false;
			break;
		}
	}

	if (!(is2 >> buf2) && Res)
	{
		Res = false;
	}

	is1.close();
	is2.close();

	return Res;
}

//AC结果评测
bool AcceptedTest(const char* program, const char* tester)
{
	ifstream is1(program);
	ifstream is2(tester);

	bool Res = true;

	string buf1;
	string buf2;

	while (getline(is2, buf2))
	{
        RemoveStringBlank(buf2);

        if (buf2 == "")
            continue;

		if (!getline(is1, buf1))
		{
			Res = false;
			break;
		}

        RemoveStringBlank(buf1);

        if (buf1 == "")
            continue;

		if (buf1 != buf2)
		{
			Res = false;
			break;
		}
	}

	if (getline(is1, buf1))
	{
		Res = false;
	}

	is1.close();
	is2.close();

	return Res;
}

int main(int argc, char **argv)
{
	int status = CORRECT;
	//输入数据
	//FILE *f_in = fopen(argv[1], "r");
	//输出数据
	//FILE *f_out = fopen(argv[2], "r");
	//用户输出数据
	//FILE *f_user_out = fopen(argv[3], "r");

	if(AcceptedTest(argv[2], argv[3]))
    {
        status = CORRECT;
    }
    else
    {
        if(PresentationErrorTest(argv[2], argv[3]))
        {
            status = PRESENTATION_ERROR;
        }
        else if(OutputLimitExceededTest(argv[2], argv[3]))
        {
            status = OUTPUTLIMIT;
        }
        else
        {
            status = WRONG_ANSWER;
        }
    }

    return status;
}
