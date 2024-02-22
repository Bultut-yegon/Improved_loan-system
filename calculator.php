<?php



$loan_amount = 100000;

$term = 24;

$remainingBal = $loan_amount;

$rate = 6;

$monthly_rate = ($rate / 12) / 100;



$emi = $loan_amount * ($monthly_rate * pow((1 + $monthly_rate), $term)) / (pow((1 + $monthly_rate), $term) - 1);
$total_interest = 0;
$total_payments = 0;