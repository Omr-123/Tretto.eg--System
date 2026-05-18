<?php
require_once __DIR__ . '/payment.php';

class Visa extends Payment
{
    private string $cardNumber;
    private string $cardHolderName;
    private string $expiryDate;
    private string $cvv;

    public function __construct(
        int $orderID,
        float $amount,
        string $cardNumber,
        string $cardHolderName,
        string $expiryDate,
        string $cvv
    ) {
        parent::__construct($orderID, $amount, 'Visa');
        $this->cardNumber = preg_replace('/\D+/', '', $cardNumber);
        $this->cardHolderName = $cardHolderName;
        $this->expiryDate = $expiryDate;
        $this->cvv = preg_replace('/\D+/', '', $cvv);
    }

    public function getMaskedCardNumber(): string
    {
        return '**** **** **** ' . substr($this->cardNumber, -4);
    }

    private function validateCard(): bool
    {
        if (!preg_match('/^\d{16}$/', $this->cardNumber)) return false;
        if (!preg_match('/^\d{3,4}$/', $this->cvv)) return false;
        if (trim($this->cardHolderName) === '') return false;
        if (strtotime($this->expiryDate) === false || strtotime($this->expiryDate) < time()) return false;
        return true;
    }

    public function authorizePayment(): bool
    {
        return $this->orderID > 0 && $this->amount > 0 && $this->validateCard();
    }

    public function processVisaPayment(): bool
    {
        if (!$this->authorizePayment()) return false;
        return $this->savePayment('Completed') > 0;
    }
}
