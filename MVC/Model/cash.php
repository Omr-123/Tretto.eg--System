<?php
require_once __DIR__ . '/payment.php';

class Cash extends Payment
{
    private float $receivedAmount;
    private float $changeAmount;
    private string $paymentLocation;

    public function __construct(int $orderID, float $amount, float $receivedAmount, string $paymentLocation = '')
    {
        parent::__construct($orderID, $amount, 'Cash');
        $this->receivedAmount = $receivedAmount;
        $this->paymentLocation = $paymentLocation;
        $this->changeAmount = $this->calculateChange();
    }

    public function getReceivedAmount(): float { return $this->receivedAmount; }
    public function getChangeAmount(): float { return $this->changeAmount; }
    public function getPaymentLocation(): string { return $this->paymentLocation; }

    public function calculateChange(): float
    {
        return $this->receivedAmount - $this->amount;
    }

    public function authorizePayment(): bool
    {
        return $this->orderID > 0 && $this->amount > 0 && $this->receivedAmount >= $this->amount;
    }

    public function processCashPayment(): bool
    {
        if (!$this->authorizePayment()) return false;
        return $this->savePayment('Completed') > 0;
    }
}
