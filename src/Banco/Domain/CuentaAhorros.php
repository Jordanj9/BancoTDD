<?php


namespace Src\Banco\Domain;


class CuentaAhorros extends CuentaBancaria
{
    private $movimientos;
    private $cont = 0;

    public function __construct(string $numero, string $nombre, string $ciudad, float $saldo) {
        parent::__construct($numero, $nombre, $ciudad, $saldo);
        $this->movimientos = [];
        if ($saldo > 0) {
            $this->AddMovimiento($this->getSaldo(), $saldo, 0.0, 'CONSIGNACION');
        }
    }

    private function AddMovimiento(float $saldoAnterior, float $valorCredito, float $valorDebito, string $tipo, string $fecha = null): void {
        $movimiento = new CuentaBancariaMovimiento($saldoAnterior, $valorCredito, $valorDebito, $tipo, $fecha);
        $this->movimientos[] = $movimiento;
    }

    function consignar(float $valorConsignacion, string $ciudadDeposito): string {

        $valorConsignacion = $this->getCiudad() != $ciudadDeposito ? $valorConsignacion - 10000 : $valorConsignacion;

        if ($valorConsignacion <= 0) return 'El valor a consignar es incorrecto';

        if ($valorConsignacion >= 50000 && !$this->tieneConsignaciones()) {
            $this->AddMovimiento($this->getSaldo(), $valorConsignacion, 0.0, 'CONSIGNACION');
            $nuevoSaldo = $this->getSaldo() + $valorConsignacion;
            $this->setSaldo($nuevoSaldo);
            return sprintf("Su Nuevo Saldo es de $%s pesos m/c", number_format($this->getSaldo(), 2));
        }

        if ($valorConsignacion < 50000 && !$this->tieneConsignaciones()) return 'El valor minimo de la primera consignación debe ser de  $50.000 mil pesos. Su nuevo saldo es $0 pesos';

        if ($this->tieneConsignaciones()) {
            $this->AddMovimiento($this->getSaldo(), $valorConsignacion, 0.0, 'CONSIGNACION');
            $nuevoSaldo = $this->getSaldo() + $valorConsignacion;
            $this->setSaldo($nuevoSaldo);
            return sprintf("Su Nuevo Saldo es de $%s pesos m/c", number_format($this->getSaldo(), 2));
        }

        return '';
    }

    function retirar(float $valorRetiro, string $fecha): string {
        date_default_timezone_set('America/Bogota');
        $hoy = date('m');
        if (count($this->movimientos) > 0) {
            foreach ($this->movimientos as $item) {
                $date = explode('/', $item->fecha);
                $mes = $date[0];
                if ($item->tipo == 'RETIRO' && $mes == $hoy)
                    $this->cont = $this->cont + 1;
            }
        }
        $valorRetiro = $this->cont > 3 ? $valorRetiro + 5000 : $valorRetiro;
        $saldo = $this->getSaldo() - $valorRetiro;
        if ($saldo >= 20000) {
            $this->AddMovimiento($this->getSaldo(), 0.0, $valorRetiro, 'RETIRO',$fecha);
            $this->setSaldo($saldo);
            return sprintf("Su Nuevo Saldo es de $%s pesos m/c.", number_format($this->getSaldo(), 2));
        } else {
            return 'Fondos insuficientes para realizar la operación.';
        }
    }

    private function tieneConsignaciones(): bool {
        return count($this->movimientos) !== 0;
    }

}
