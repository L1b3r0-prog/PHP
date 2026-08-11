<?php
class BankAccount {
		public float $balance = 720.00;
		public function withdrawal(float $Amount):void {
			   $this->balance -= $Amount;
		}
}
if (class_exists("BankAccount"))
   $checking = new BankAccount();
else
   exit("<p>The BankAccount class is not available!</p>");

echo "<p>Your checking account balance is \${$checking->balance}</p>";
$cash = 200;
$checking->withdrawal($cash);
echo "<p>After withdrawing \${$cash}, your checking account balance is \${$checking->balance}</p>";

?>

