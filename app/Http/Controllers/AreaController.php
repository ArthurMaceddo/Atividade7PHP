<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function calcularArea(Request $request, string $forma)
    {
        $model = $this->obterModelo($forma);

        if ($model === null) {
            return redirect()->route('erro'); //mudei aqui
        }

        $area = $this->calcArea($request, $model);

        return view('resultado', ['area' => $area]);
    }

    private function obterModelo(string $forma): ?CalculoArea
    {
        switch ($forma) {
            case 'quadrado':
                return new Quadrado($request->query('lado'));
            case 'retangulo':
                return new Retangulo($request->query('base'), $request->query('altura'));
            case 'circulo':
                return new Ciruclo($request->query('raio'));
            default:
                return null;
        }
    }

    private function calcArea(Request $request, CalculoArea $model): float
    {
        $area = 0;

        if ($this->validarAtributos($request, $model)) {
            $area = $model->calcArea();
        }

        return $area;
    }

    private function validarAtributos(Request $request, CalculoArea $model): bool
    {
        $atributos = $model->obterAtributos();

        foreach ($atributos as $atributo) {
            if (!$request->has($atributo)) {
                return false;
            }
        }

        return true;
    }
}
