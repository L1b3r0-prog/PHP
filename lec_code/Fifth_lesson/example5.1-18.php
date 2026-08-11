<?php
    class BankAccount {
        private float $balance;
        
        function __construct(float $bal =0){
            $this->balance = $bal;
        }
        public function setBalance(float $newValue) {
              if ($newValue >0)
                  $this->balance = $newValue;
        }
        public function getBalance(): float {
              return $this->balance;
        }
    }
    
    $checking = new BankAccount();
    $checking->setBalance(100);
    echo "<p>Your checking account balance is " . $checking->getBalance() . "</p>\n";
    $newCh = new BankAccount();
    $newCh->setBalance(-50);
    echo "<p>Your checking account balance is " . $newCh->getBalance() . "</p>\n";
    $newCh = new BankAccount(-150);
    echo "<p>Your checking account balance is " . $newCh->getBalance() . "</p>\n";

?>
