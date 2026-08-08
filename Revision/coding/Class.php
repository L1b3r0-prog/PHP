<?php
    class BankAccount {
        public float $balance = 120.00;
        public function withdraw(float $amount): void {
            $this->balance -= $amount;
        }
    }

    if (class_exists("BankAccount"))
        $checking = new BankAccount();
    else
        exit("The BankAccount class is not available");

    echo "Your checking account balance is \${$checking->balance}";
    $cash = 10;
    $checking->withdraw($cash);
    echo "After withdrawing \${$cash}, your balance is \${$checking->balance}";
?>