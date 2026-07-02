<?php
    class BankAccount {
        public float $bal = 1200.0;
        public function withdrawal(float $Amount) : void {
            $this->bal -= $Amount;
        }
    }

    if (class_exists("BankAccount"))
        $checking = new BankAccount();
    else
        exit ("<p>The BankAccount class is not available!</p>");

    echo "<p>Your checking account balance is \${$checking->bal}</p>";
    $cash = 200;
    $checking->withdrawal($cash);
    echo "<p>After withdrawing \${$cash}, your checking account balance is \${$checking->bal}</p>";
?>