package ntb.bachelor;

public class Main {

    public static void main(String[] args) {
        System.out.println("Hello hello");


        // Start some threads
        ExampleThread exampleThread1 = new ExampleThread("Primo");
        exampleThread1.start();

        ExampleThread exampleThread2 = new ExampleThread("Secondo");
        exampleThread2.start();


    }
}
