<?php


namespace Src\Banco\Domain;


class CuentaBancariaMovimiento
{
    public $saldoAnterior;
    public $valorCredito;
    public $valorDebito;
    public $fecha;
    public $tipo;


    public function __construct(float $saldoAnterior, float $valorCredito, float $valorDebito, string $tipo, string $fecha = null) {
        $this->saldoAnterior = $saldoAnterior;
        $this->valorCredito = $valorCredito;
        $this->valorDebito = $valorDebito;
        $this->fecha = $fecha;
        $this->tipo = $tipo;
    }

}
