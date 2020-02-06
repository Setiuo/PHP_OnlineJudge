#include <cstdio>
#include <iostream>
#include <fstream>
#include <cmath>
#include <algorithm>
#include <string>
#include <vector>

using namespace std;

double rating = 0.3;
const int reward = 3;

struct user {
	double rank;
	string handle;
	int old_rating;
	int new_rating = 0;
	double seed = 1.0;
	int delta = 0;
	string validation;
} u;

vector<user> users;

// Functions for user struct

bool cmp_old_rating_desc(user a, user b) {
	return a.old_rating > b.old_rating;
}

bool cmp_rank_asc(user a, user b) {
	return a.rank < b.rank;
}

string to_res(user& u) {
	char s[200];
	sprintf(s, "%g %s %d %d\n",
		round(u.rank),
		u.handle.c_str(),
		u.old_rating,
		u.new_rating
	);
	return string(s);
}

string to_res(vector<user>& users) {
	string s;
	for (int i = 0; i < users.size(); i++) {
		s += to_res(users[i]);
	}
	return s;
}
// Functions for calculation

double cal_p(user& a, user& b) {
	return 1.0 / (1.0 + pow(10, (b.old_rating - a.old_rating) / 400.0));
}

double cal_seed(int idx, int rating) {
	user ex_user;
	ex_user.old_rating = rating;
	double res = 1.0;
	for (int i = 0; i < users.size(); i++) {
		if (i != idx) {
			res += cal_p(users[i], ex_user);
		}
	}
	return res;
}

int cal_rating_to_rank(int idx, double rank) {
	int l = 1, r = 8000;
	while (r - l > 1) {
		int mid = (l + r) / 2;
		if (cal_seed(idx, mid) < rank) {
			r = mid;
		}
		else {
			l = mid;
		}
	}
	return l;
}

void work() {
	// Calculate seed
	for (int i = 0; i < users.size(); i++) {
		for (int j = 0; j < users.size(); ++j) {
			if (i != j) {
				users[i].seed += cal_p(users[j], users[i]);
			}
		}
	}
	// Calculate initial delta and sum_delta
	int sum_delta = 0;
	for (int i = 0; i < users.size(); i++) {
		double m = sqrt(users[i].rank * users[i].seed);
		int R = cal_rating_to_rank(i, m);
		users[i].delta = (R - users[i].old_rating) / 2;
		sum_delta += users[i].delta;
	}
	// Calculate the first inc
	int inc = -(sum_delta / (int)users.size()) - 1;
	for (int i = 0; i < users.size(); i++) {
		users[i].delta += inc;
	}
	// Calculate the second inc
	sort(users.begin(), users.end(), cmp_old_rating_desc);
	int s = min((int)(users.size()), (int)(4 * round(sqrt(users.size()))));
	int sum_s = 0;
	for (int i = 0; i < s; i++) {
		sum_s += users[i].delta;
	}
	inc = min(max(-(sum_s / s), -10), 0);
	for (int i = 0; i < users.size(); i++) {
		users[i].delta += inc;
	}
	// Calculate new rating
	for (int i = 0; i < users.size(); i++) {
		users[i].new_rating = users[i].old_rating + users[i].delta * rating + reward;
	}
	sort(users.begin(), users.end(), cmp_rank_asc);
}

void reassign_rank() {
	int last_idx = 0, last_rank = 1;
	for (int i = 0; i < users.size(); i++) {
		if (users[i].rank > last_rank) {
			for (int j = last_idx; j < i; ++j) {
				users[j].rank = i;
			}
			last_idx = i;
			last_rank = users[i].rank;
		}
	}
	for (int i = last_idx; i < users.size(); i++) {
		users[i].rank = users.size();
	}
}

int main(int argc, char const* argv[]) {
	if (argc < 2) {
		printf("Usage: ./file_name [codeforces_contest_id]\n");
		return 1;
	}
	string contest_id = string(argv[1]);
	string in_file = "./data/cf_rating_start_" + contest_id + ".txt";
	string res_file = "./data/cf_rating_end_" + contest_id + ".txt";

	ifstream in(in_file, ios::in);

	in >> rating;
	while (in >> u.rank >> u.handle >> u.old_rating) {
		users.push_back(u);
	}
	in.close();
	if (users.empty()) {
		printf("%s\n", in_file.c_str());
		printf("Invalid contest\n");
		return 1;
	}

	reassign_rank();
	work();

	ofstream res_out(res_file, ios::out);
	res_out << to_res(users);
	res_out.close();

	printf("success\n");
	return 0;
}