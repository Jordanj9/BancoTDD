<?php


namespace Src\Banco\Domain;


abstract class CuentaBancaria
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

    abstract function consignar(float $valorConsignacion, string $ciudadDeposito): string;

    abstract function retirar(float $valorRetiro,string $fecha): string;

}
