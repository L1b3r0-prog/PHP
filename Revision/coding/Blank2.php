<?php
    class BankAccount {
        private float $balance = 0;
        public function __construct(float $bal = 0){
            $this->balance = $bal;
        }

        public function getBalance():float{
            return $this->balance;
        }

        public function setBalance(float $newValue):void{
            if ($newValue>0) {
                $this->balance = $newValue;
            }
        }

        public function withdraw(float $withdraw):void {
            if ($withdraw>0 && $withdraw<=$this->balance){
                $this->balance -= $withdraw;
            }
        }

        public function topup(float $topup):void{
            if ($topup>0){
                $this->balance += $topup;
            }
        }
    }

    $checking = new BankAccount();
    $checking->setBalance(2000);
    echo "Your account has " . $checking->getBalance();

    $checking->withdraw(500);
    echo "Your accoutn after withdrawal is " . $checking->getBalance();

    $checking->topup(3500);
    echo "Your account after topup is " . $checking->getBalance();
?>