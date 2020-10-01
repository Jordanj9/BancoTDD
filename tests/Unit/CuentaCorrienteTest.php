<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Src\Banco\Domain\CuentaCorriente;

class CuentaCorrienteTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Escenario: Consignación Inicial Correcta
     * HU: Como Usuario quiero realizar consignaciones a una cuenta corriente para salvaguardar el dinero.
     * Criterio de Aceptación:
     * 1.1 La consignación inicial debe ser mayor o igual a 100 mil pesos
     * 1.3 El valor de la consignación se le adicionará al valor del saldo aumentará
     * Dado El cliente tiene una cuenta corriente
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 0 ciudad valledupar
     * Cuando Va a consignar el valor inicial de 100 mil pesos
     * Entonces El sistema registrará la consignación
     * AND presentará el mensaje. "Su Nuevo Saldo es de $50.000,00 pesos m/c".
     * @test
     */
    public function testConsignacionInicialCorrecta(): void {
        $cuentaCorriente = new CuentaCorriente('10001', 'Cuenta ejemplo', 'Valledupar', 0.0,500000);
        $result = $cuentaCorriente->consignar(100000, 'Valledupar');
        $this->assertEquals('Su Nuevo Saldo es de $100,000.00 pesos m/c', $result);
    }

    /**
     * Escenario: Retirar cuenta corriente aplicando el 4xMil
     * HU: Como Usuario quiero realizar retiros a una cuenta corriente para obtener el dinero en efectivo.
     * Criterio de Aceptación:
     * 2.1 El valor a retirar se debe descontar del saldo de la cuenta.
     * 4.3 El retiro tendrá un costo del 4×Mil
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 300000 , ciudad Valledupar
     * Cuando Va a retirar 60000
     * Entonces El sistema presentar el mensaje. "Fondos insuficientes para realizar la operación."
     * @test
     */
    public function testRetiroCorrecto(): void {
        $cuentaCorriente = new CuentaCorriente('10001', 'Cuenta Ejemplo', 'Valledupar', 300000,100000);
        $result = $cuentaCorriente->retirar(60000, '20/07/2020');
        $this->assertEquals('Su Nuevo Saldo es de $239,760.00 pesos m/c.', $result);
    }


    /**
     * Escenario: Retirar cuenta corriente con saldo minimo mayor que el sobregiro
     * HU: Como Usuario quiero realizar retiros a una cuenta corriente para obtener el dinero en efectivo.
     * Criterio de Aceptación:
     * 2.1 El valor a retirar se debe descontar del saldo de la cuenta.
     * 4.2 El saldo mínimo deberá ser mayor o igual al cupo de sobregiro
     * 4.3 El retiro tendrá un costo del 4×Mil
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 300000 , ciudad Valledupar
     * Cuando Va a retirar 60000
     * Entonces El sistema presentar el mensaje. "Fondos insuficientes para realizar la operación."
     * @test
     */
    public function testRetiroSaldoMinimoSobregiroCorrecto(): void {
        $cuentaCorriente = new CuentaCorriente('10001', 'Cuenta Ejemplo', 'Valledupar', 1000000,500000);
        $result = $cuentaCorriente->retirar(200000, '20/07/2020');
        $this->assertEquals('Su Nuevo Saldo es de $799,200.00 pesos m/c.', $result);
    }

    /**
     * Escenario: Retirar cuenta corriente con saldo minimo menor que el sobregiro
     * HU: Como Usuario quiero realizar retiros a una cuenta corriente para obtener el dinero en efectivo.
     * Criterio de Aceptación:
     * 2.1 El valor a retirar se debe descontar del saldo de la cuenta.
     * 4.2 El saldo mínimo deberá ser mayor o igual al cupo de sobregiro
     * 4.3 El retiro tendrá un costo del 4×Mil
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 400000 , ciudad Valledupar,fecha 20/07/2020, sobregiro 300000
     * Cuando Va a retirar 120000
     * Entonces El sistema presentar el mensaje. "Fondos insuficientes para realizar la operación."
     * @test
     */
    public function testRetiroSaldoMinimoSobregiroIncorrecto(): void {
        $cuentaCorriente = new CuentaCorriente('10001', 'Cuenta Ejemplo', 'Valledupar', 400000,300000);
        $result = $cuentaCorriente->retirar(120000, '20/07/2020');
        $this->assertEquals('Fondos insuficientes, el saldo el menor que el sobregiro. Operación cancelada.', $result);
    }
}
