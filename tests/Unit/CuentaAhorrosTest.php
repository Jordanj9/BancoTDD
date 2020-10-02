<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Src\Banco\Domain\CuentaAhorros;

class CuentaAhorrosTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample() {
        $this->assertTrue(true);
    }

    /**
     * Escenario: Valor de consignación negativo o cero
     * H1: Como Usuario quiero realizar consignaciones a una cuenta de ahorro para salvaguardar el dinero.
     * Criterio de Aceptación:
     * 1.2 El valor a abono no puede ser menor o igual a 0
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro                                       //A =>Arrange /Preparación
     * N�mero 10001, Nombre "Cuenta ejemplo", Saldo de 0 , ciudad Valledupar
     * Cuando Va a consignar un valor menor o igual a cero  (0)                            //A =>Act = Acción
     * Entonces El sistema presentar el mensaje. "El valor a consignar es incorrecto"  //A => Assert => Validación
     * @tests
     */
    public function testValorConsignacionNegativoCero(): void {
        $cuentaAhorros = new CuentaAhorros('10001', 'Cuenta ejemplo', 'Valledupar', 0.0);
        $resultado = $cuentaAhorros->consignar(0, 'Valledupar');
        $this->assertEquals('El valor a consignar es incorrecto', $resultado);
    }

    /**
     * Escenario: Consignación Inicial Correcta
     * HU: Como Usuario quiero realizar consignaciones a una cuenta de ahorro para salvaguardar el dinero.
     * Criterio de Aceptación:
     * 1.1 La consignación inicial debe ser mayor o igual a 50 mil pesos
     * 1.3 El valor de la consignación se le adicionará al valor del saldo aumentará
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 0 ciudad valledupar
     * Cuando Va a consignar el valor inicial de 50 mil pesos
     * Entonces El sistema registrará la consignación
     * AND presentará el mensaje. "Su Nuevo Saldo es de $50.000,00 pesos m/c".
     * @test
     */

    public function testConsignacionCorrecta(): void {
        $cuentaAhorro = new CuentaAhorros('10001', 'Cuenta ejemplo', 'Valledupar', 0.0);
        $resultado = $cuentaAhorro->consignar(50000, 'Valledupar');
        $this->assertEquals('Su Nuevo Saldo es de $50,000.00 pesos m/c', $resultado);
    }

    /**
     * Escenario: Consignación Inicial Incorrecta
     * HU: Como Usuario quiero realizar consignaciones a una cuenta de ahorro para salvaguardar el dinero.
     * Criterio de Aceptación:
     * 1.1 La consignación inicial debe ser mayor o igual a 50 mil pesos
     * Dado El cliente tiene una cuenta de ahorro con
     * Número 10001, Nombre “Cuenta ejemplo”, Saldo de 0 ciudad Valledupar
     * Cuando Va a consignar el valor inicial de $49.999 pesos
     * Entonces El sistema no registrará la consignación
     * AND presentará el mensaje. “El valor mínimo de la primera consignación debe ser de $50.000 mil pesos. Su nuevo saldo es $0 pesos”.
     * @tests
     */
    public function testNoPuedeConsignarMenosDeCincuentaMilPesos(): void {
        //Preparar
        $cuentaAhorros = new CuentaAhorros('10001', 'Cuenta ejemplo', 'Valledupar', 0.0);
        //Acción
        $resultado = $cuentaAhorros->consignar(49950, 'Valledupar');
        //$verificación
        $this->assertEquals('El valor minimo de la primera consignación debe ser de  $50.000 mil pesos. Su nuevo saldo es $0 pesos', $resultado);
    }

    /**
     * Escenario: Consignación posterior a la inicial correcta
     * HU: Como Usuario quiero realizar consignaciones a una cuenta de ahorro para salvaguardar el
     * dinero.
     * Criterio de Aceptación:
     * 1.3 El valor de la consignación se le adicionará al valor del saldo aumentará
     * Dado El cliente tiene una cuenta de ahorro con un saldo de 30.000
     * Cuando Va a consignar el valor inicial de $49.950 pesos
     * Entonces El sistema registrará la consignación
     * AND presentará el mensaje. “Su Nuevo Saldo es de $79.950,00 pesos m/c”.
     * @test
     */
    public function testConsignacionPosteriorInicialCorrecta(): void {
        $cuentaAhorros = new CuentaAhorros('10001', 'Cuenta ejemplo', 'Valledupar', 30000);
        $result = $cuentaAhorros->consignar(49950, 'Valledupar');
        $this->assertEquals('Su Nuevo Saldo es de $79,950.00 pesos m/c', $result);
    }

    /**
     * Escenario: Consignación posterior a la inicial correcta
     * HU: Como Usuario quiero realizar consignaciones a una cuenta de ahorro para salvaguardar el
     * dinero.
     * Criterio de Aceptación:
     * 1.4 La consignación nacional (a una cuenta de otra ciudad) tendrá un costo de $10 mil pesos.
     * Dado El cliente tiene una cuenta de ahorro con un saldo de 30.000 perteneciente a una
     * sucursal de la ciudad de Bogotá y se realizará una consignación desde una sucursal
     * de la Valledupar.
     * Cuando Va a consignar el valor inicial de $49.950 pesos.
     * Entonces El sistema registrará la consignación restando el valor a consignar los 10 mil pesos.
     * AND presentará el mensaje. “Su Nuevo Saldo es de $69.950,00 pesos m/c”.
     * @test
     */
    public function testConsignacionPosteriorInicialCorrectaNacional(): void {
        $cuentaAhorros = new CuentaAhorros('10001', 'Cuenta ejemplo', 'Bogotá', 30000);
        $result = $cuentaAhorros->consignar(49950, 'Valledupar');
        $this->assertEquals('Su Nuevo Saldo es de $69,950.00 pesos m/c', $result);
    }

    /**
     * Escenario: Retirar cuenta de ahorros saldo minimo 20000
     * HU: Como Usuario quiero realizar retiros a una cuenta de ahorro para obtener el dinero en efectivo.
     * Criterio de Aceptación:
     * 2.1 El valor a retirar se debe descontar del saldo de la cuenta.
     * 2.2 El saldo mínimo de la cuenta deberá ser de 20 mil pesos
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 10000 , ciudad Valledupar
     * Cuando Va a retirar 60000
     * Entonces El sistema presentar el mensaje. "Fondos insuficientes para realizar la operación."
     * @test
     */
    public function testRetiroSaldoMinimoIncorrecto(): void {
        $cuentaAhorro = new CuentaAhorros('10001', 'Cuenta Ejemplo', 'Valledupar', 10000);
        $result = $cuentaAhorro->retirar(60000, '20/07/2020');
        $this->assertEquals('Fondos insuficientes para realizar la operación.', $result);
    }

    /**
     * Escenario: Retiro cuenta de ahorros saldo minimo correcto
     * HU: Como Usuario quiero realizar retiros a una cuenta de ahorro para obtener el dinero en efectivo.
     * Criterio de Aceptación:
     * 2.1 El valor a retirar se debe descontar del saldo de la cuenta.
     * 2.2 El saldo mínimo de la cuenta deberá ser de 20 mil pesos
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 50000 , ciudad Valledupar
     * Cuando Va a retirar 30000
     * Entonces El sistema presentar el mensaje. "Su Nuevo Saldo es de $20.000,00 pesos m/c."
     * @test
     */
    public function testRetiroSaldoMinimoCorrecto(): void {
        $cuentaAhorro = new CuentaAhorros('10001', 'Cuenta Ejemplo', 'Valledupar', 50000);
        $result = $cuentaAhorro->retirar(30000, '15/08/2020');
        $this->assertEquals('Su Nuevo Saldo es de $20,000.00 pesos m/c.', $result);
    }

    /**
     * Escenario: Retiro cuenta de ahorros validación numeros de retiros mes
     * HU: Como Usuario quiero realizar retiros a una cuenta de ahorro para obtener el dinero en efectivo.
     * Criterio de Aceptación:
     * 2.3 Los primeros 3 retiros del mes no tendrán costo.
     * 2.4 Del cuarto retiro en adelante del mes tendrán un valor de 5 mil pesos
     * Ejemplo
     * Dado El cliente tiene una cuenta de ahorro
     * Número 10001, Nombre "Cuenta ejemplo", Saldo de 500000 , ciudad Valledupar
     * Cuando
     * va a realizar cuatro retiros en cuatro fechas distintas del mes de marzo
     * la primer el día  1/06/2020 por $100,000.00 pesos m/c
     * la segunda el día 15/06/2020  por $70,000.00 pesos m/c
     * la tercera el día 24/06/2020 por $30,000.00 pesos m/c
     * la tercera el día 28/06/2020 por $30,000.00 pesos m/c
     * Entonces El sistema presentar el mensaje. "Su Nuevo Saldo es de $20.000,00 pesos m/c."
     * @test
     */
    public function testRetiroDelMesSinCosto(): void {
        $cuentaAhorro = new CuentaAhorros('10001', 'Cuenta ejemplo', 'Valledupar', 500000);

        $retiros = ['10/5/2020' => 100000, '10/10/2020' => 70000, '10/14/2020' => 30000, '10/28/2020' => 30000,'10/29/2020'=>20000];
        foreach ($retiros as $key => $value) {
            $result = $cuentaAhorro->retirar($value, $key);
        }
        $this->assertEquals('Su Nuevo Saldo es de $245,000.00 pesos m/c.', $result);
    }
}
