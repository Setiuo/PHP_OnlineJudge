import java.util.*;
import java.io.*;

public class Main {

    public static int Count(long n) {
        int count = 0;
        int i = 1;

        for (; i * i < n; i++) {
            if (n % i == 0) {
                count += 2;
            }
        }

        if (i * i == n)
            count++;

        return count;
    }

    public static void main(String[] args) {
        Scanner Sc = new Scanner(System.in);
        long n = Sc.nextInt();

        int Ans = Count(n);

        System.out.print(Ans);
    }
}