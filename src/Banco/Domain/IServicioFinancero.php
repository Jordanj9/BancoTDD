<?php


namespace Src\Banco\Domain;


interface IServicioFinancero
{
    public function consignar(float $valorConsignacion, string $ciudadDeposito): string;

    public function retirar(float $valorRetiro,string $fecha): string;

}
