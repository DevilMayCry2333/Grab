
public class Main {

    public static void main(String[] args) {
        String a="";
        for (int i= 0 ; i < args.length ; i++)
            a = a + args[i];
        MailUtil.sendEmil("XXXXX@xx.com",a);
    }
}
