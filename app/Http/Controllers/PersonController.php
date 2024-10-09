<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\PersonRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    
    public function list(Request $request): JsonResponse{

        $filterField = '';
        $filterValue = '';
        $filterOperator = '';
        $filterRequest = $request->get('filter');
        if ($filterRequest) {
            $filterField = 'birth_date';
            $filterValue = Carbon::parse('-'.config('app.adults_min_age').'years')->format('Y-m-d');
            switch($filterRequest){
                case 'adults':
                    $filterOperator = '<=';
                break;
                case 'minors':
                    $filterOperator = '>';
                break;
                default:
                    $filterField = '';
            }
        }
        

        $people = PersonRepository::listPeople($filterField, $filterValue, $filterOperator);

        return response()->json($people);
    }

    public function save(Request $request){
        $data = json_decode($request->getContent(),true);
        
        $validator = Validator::make(
            $data,
            [
                'name' => ['required','max:150',"regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'-]+$/"],
                'lastName' => ['required','max:150',"regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'-]+$/"],
                'birthDate' => ['required','date','before:'.Carbon::tomorrow()->format('Y-m-d')],
            ],
            [
                'name.required' => 'Debes indicar un nombre',
                'name.max' => 'El nombre supera la longitud permitida',
                'name.regex' => 'El nombre tiene caracteres no permitidos',
                'lastName.required' => 'Debes indicar los apellidos',
                'lastName.max' => 'Los apellidos superan la longitud permitida',
                'lastName.regex' => 'Los apellidos tienen caracteres no permitidos',
                'birthDate.required' => 'La fecha es obligatoria',
                'birthDate.date' => 'El formato de la fecha no es correcto',
                'birthDate.before' => 'La fecha no puede ser superior a la actual',
            ],
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        } else {
            $response = response('',200);
            try{
                PersonRepository::registerPerson($data['name'],$data['lastName'], $data['birthDate']);
            }catch(\Exception $e){
                $response->setStatusCode(500);
            }
            return $response;
        }

    }

    public function remove(int $id){
        $response = response('',200);
        try{
            PersonRepository::deletePerson($id);
        }catch(\Exception $e){
            $response->setStatusCode(500);
        }
        return $response;
    }
}
