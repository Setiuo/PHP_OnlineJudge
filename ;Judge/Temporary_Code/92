#include <iostream>

using namespace std;
typedef long long ll;

ll ans(ll n, ll m)
{
	if (n % m)
		return ans(m, n % m);
	else
		return m;
}
int main()
{
	ll n, m;
	while (cin >> n >> m)
	{
		cout << ans(n, m) << endl;
	}
	return 0;
}