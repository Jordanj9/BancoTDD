<?php


namespace Src\Banco\Domain;


class CuentaCorriente extends CuentaBancaria
{
    private $movimientos;
    private $sobregiro;

    public function __construct(string $numero, string $nombre, string $ciudad, float $saldo,float $sobregiro) {
        parent::__construct($numero, $nombre, $ciudad, $saldo);
        $this->sobregiro = $sobregiro;
        $this->movimientos = [];
    }

    private function AddMovimiento(float $saldoAnterior, float $valorCredito, float $valorDebito, string $tipo, string $fecha = null): void {
        $movimiento = new CuentaBancariaMovimiento($saldoAnterior, $valorCredito, $valorDebito, $tipo, $fecha);
        $this->movimientos[] = $movimiento;
    }

    function consignar(float $valorConsignacion, string $ciudadDeposito): string {
        if ($valorConsignacion >= 100000 && !$this->tieneConsignaciones()) {
            $this->AddMovimiento($this->getSaldo(), $valorConsignacion, 0.0, 'CONSIGNACION');
            $nuevoSaldo = $this->getSaldo() + $valorConsignacion;
            $this->setSaldo($nuevoSaldo);
            return sprintf("Su Nuevo Saldo es de $%s pesos m/c", number_format($this->getSaldo(), 2));
        }
    }

    public function retirar(float $valorRetiro, string $fecha): string {
        $saldo = $this->getSaldo() - $valorRetiro - $valorRetiro / 250;
        if($saldo < $this->sobregiro) return 'Fondos insuficientes, el saldo el menor que el sobregiro. Operación cancelada.';
        $saldo = ($this->getSaldo() - $valorRetiro) - ($valorRetiro / 250);
        $this->AddMovimiento($this->getSaldo(), 0.0, $valorRetiro, 'RETIRO', $fecha);
        $this->setSaldo($saldo);
        return sprintf("Su Nuevo Saldo es de $%s pesos m/c.", number_format($this->getSaldo(), 2));
    }

    private function tieneConsignaciones(): bool {
        return count($this->movimientos) !== 0;
    }

}
