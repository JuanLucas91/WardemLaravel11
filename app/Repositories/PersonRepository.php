<?php

namespace App\Repositories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class PersonRepository{

    /**
     * Creates and stores a new Person
     * @param string $name
     * @param string $lastName
     * @param string $date
     * @return void
     */
    public static function registerPerson(string $name, string $lastName, string $date): void{
        $person = new Person();
        $person->name = $name;
        $person->last_name = $lastName;
        $person->birth_date = Carbon::parse($date);
        $person->save();
    }

    /**
     * Removes a Person by it's ID
     * @param int $id
     * @return void
     */
    public static function deletePerson(int $id): void{
        $person = Person::find($id);
        $person->delete();
    }

    /**
     * Gets all registered people, or retrieves a filtered collection
     * @param string $filterField
     * @param string $filterValue
     * @param string $filterOperator
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function listPeople(string $filterField = '', string $filterValue = '', string $filterOperator = '='): Collection{
        $people = new Collection();

        $filterField ?
        $people = Person::where($filterField, $filterOperator, $filterValue)->get()
        : $people = Person::all();

        return $people;
    }


}