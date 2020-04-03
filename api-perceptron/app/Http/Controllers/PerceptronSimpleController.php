<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Matriz_compuerta;

class PerceptronSimpleController extends Controller
{
    public function entrenarNeurona(Request $request)
    {
    	$params_array = array(
            'w1' => $request["w1"],
            'w2' => $request["w2"],
            'w3' => $request["w3"],
            'w4' => $request["w4"],
            'w5' => $request["w5"],
            'e' => $request["e"],
            'theta' => $request["theta"], 
        );
        if (!empty($params_array)) {
            $validate = \Validator::make($params_array,[
    			'w1'=>'required|numeric',
    			'w2'=>'required|numeric',
    			'w3'=>'required|numeric',
                'w4'=>'required|numeric',
    			'w5'=>'required|numeric',
    			'e'=>'required|numeric',
    			'theta'=>'required|numeric',
    		]);
            if ($validate->fails()) {
    			$data = array(
    				'code' => 400, 
    				'status' => "error", 
    				'message' => "Faltan Datos",
    				'errors' => $validate->errors() 
    			);
    		} else{
                $w1 = $params_array["w1"];
    			$w2 = $params_array["w2"];
    			$w3 = $params_array["w3"];
                $w4 = $params_array["w4"];
    			$w5 = $params_array["w5"];

    			$theta = $params_array["theta"];
    			$e = $params_array["e"];
    			$count = 0;
				$salir = 0;
                $iteraciones = array();
				$matriz = Matriz_compuerta::all();
                while ($count != count($matriz) && $salir <= 2000) {
					foreach ($matriz as $row) {
                        $iteracion = (($w1*$row->c)+($w2*$row->i)+($w3*$row->g)+($w4*$row->x)-($w5*$row->m)-$theta);
                        $iteracion = $iteracion/100000000000;
                        $rango_superior = $row->pib*1.2;
                        $rango_inferior = $row->pib-($row->pib*0.2);
                        $iteraciones[]= $iteracion.">=".$row->pib.">=".$rango_inferior."=>".$rango_superior;
						if ($iteracion >= $rango_inferior && $iteracion<=$rango_superior) {
							$count++;
						} else{
                            $w1 =round(($w1 + 2 * $e * $row->pib * $row->c), 2); 
                            $w2 =round(($w2 + 2 * $e * $row->pib * $row->i), 2); 
                            $w3 =round(($w3 + 2 * $e * $row->pib * $row->g), 2); 
                            $w4 =round(($w4 + 2 * $e * $row->pib * $row->x), 2); 
							$w5 =round(($w5 + 2 * $e * $row->pib * $row->m), 2); 
							$theta = $theta +2 * $e * $row->pib * (-1);
				            $salir++;
				            $count = 0;
				            break;
						}
					}
				}
                if ($salir>2000) {
                    $data = array(
                        'code' => 404, 
                        'status' => "error",
                        'w1'=>$w1, 
                        'w2'=>$w2, 
                        'w3'=>$w3, 
                        'w4'=>$w4, 
                        'w5'=>$w5, 
                        'e'=>$e, 
                        'theta'=>$theta, 
                        'ecuacion'=> '('.$w1.'C + '.$w2.'I + '.$w3.'G + '.$w4.'X - '.$w5.'M) - '.$theta,
                        "iteraciones" => $iteraciones,
                        'message' => "Se alcanzo el numero maximo de iteraciones"
                    );
                } else{
                    $data = array(
                        'code' => 200, 
                        'status' => "success",
                        'w1'=>$w1, 
                        'w2'=>$w2, 
                        'w3'=>$w3, 
                        'w4'=>$w4, 
                        'w5'=>$w5, 
                        'e'=>$e, 
                        'theta'=>$theta, 
                        'ecuacion'=> '('.$w1.'C + '.$w2.'I + '.$w3.'G + '.$w4.'X - '.$w5.'M) - '.$theta,
                        "iteraciones" => $iteraciones,
                        'message' => "Se encontraron los valores validos para w"
                    );
                }
			}
    	}
        return response()->json($data,$data["code"]);
    }
    public function calcularNeurona(Request $request)
    {
        $params_array = array(
            'w1' => $request["w1"],
            'w2' => $request["w2"],
            'w3' => $request["w3"],
            'w4' => $request["w4"],
            'w5' => $request["w5"],
            'e' => $request["e"],
            'theta' => $request["theta"],
            'c'=> $request["c"],
            'i'=> $request["i"],
            'g'=> $request["g"],
            'x'=> $request["x"],
            'm'=> $request["m"]
        );
        if (!empty($params_array)) {
            $validate = \Validator::make($params_array,[
                'c'=>'required|numeric',
                'i'=>'required|numeric',
                'g'=>'required|numeric',
                'x'=>'required|numeric',
                'm'=>'required|numeric'
            ]);
            if ($validate->fails()) {
                $data = array(
                    'code' => 400, 
                    'status' => "error", 
                    'message' => "Faltan Datos",
                    'errors' => $validate->errors() 
                );
            } else{
                $w1 = $params_array["w1"];
                $w2 = $params_array["w2"];
                $w3 = $params_array["w3"];
                $w4 = $params_array["w4"];
                $w5 = $params_array["w5"];
                $theta = $params_array["theta"];

                $c = $params_array["c"];
                $i = $params_array["i"];
                $g = $params_array["g"];
                $x = $params_array["x"];
                $m = $params_array["m"];

                $pib = (($w1*$c)+($w2*$i)+($w3*$g)+($w4*$x)-($w5*$m)-$theta);
                $pib = $pib/100000000000;
                $data = array(
                    'code' => 200, 
                    'status' => "success",
                    'pib'=>number_format($pib),
                    'message' => "Se ha encontrado un valor del pib"
                );
            }
        }
        return response()->json($data,$data["code"]);

    }
}
