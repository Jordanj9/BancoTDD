<?php


namespace Src\Banco\Domain;


abstract class CuentaBancaria implements IServicioFinancero

{
    private $numero;
    private $ciudad;
    private $saldo;
    private $nombre;

    public function __construct(string $numero, string $nombre, string $ciudad, float $saldo) {
        $this->numero = $numero;
        $this->nombre = $nombre;
        $this->ciudad = $ciudad;
        $this->saldo = $saldo;
    }

    /**
     * @return string
     */
    public function getCiudad(): string {
        return $this->ciudad;
    }

    /**
     * @return string
     */
    public function getNumero(): string {
        return $this->numero;
    }

    /**
     * @return float
     */
    public function getSaldo(): float {
        return $this->saldo;
    }

    public function setSaldo(float $saldo) {
        $this->saldo = $saldo;
    }



}
