<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Person Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <article class="container">
        <div id="alertBanner" class="alert" role="alert" style="display: none"></div>
        <div class="row mt-5">
            <div class="col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                <div class="mb-3">
                    <label for="nameInput" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nameInput">
                    <div id="nameErrors" class="invalid-feedback" style="display: none"></div>
                </div>
                <div class="mb-3">
                    <label for="lastNameInput" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="lastNameInput">
                    <div id="lastNameErrors" class="invalid-feedback" style="display: none"></div>
                </div>
                <div class="mb-3">
                    <label for="birthDateInput" class="form-label">Fecha de nacimiento</label>
                    <input type="date" max="{{now()->format('Y-m-d')}}" class="form-control" id="birthDateInput">
                    <div id="birthDateErrors" class="invalid-feedback" style="display: none"></div>
                </div>
                <div class="text-center">
                    <button id="saveButton" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3">
                <h3 class="text-center">Personas registradas</h3>
                <div>
                    <p id="defaultText" class="text-center">Aún no hay datos que mostrar.</p>
                    <div id="filters" class="mt-3" style="display: none">
                        <span>Filtrar por edad: </span>
                        <button class="btn btn-secondary" id="buttonFilterAll">Todos</button>
                        <button class="btn btn-success" id="buttonFilterAdults">Adultos</button>
                        <button class="btn btn-warning" id="buttonFilterMinors">Menores</button>
                    </div>
                    <table id="peopleTable" class="table mt-3" style="display: none">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Fecha nacimiento</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="peopleList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </article>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        const listEndpoint = '{{route('listPeople')}}';
        const deleteEndpoint = '{{route('deletePerson','placeholder')}}';

        //Events
        saveButton.onclick = save;
        buttonFilterAll.onclick = () => load();
        buttonFilterAdults.onclick = () => load('adults');
        buttonFilterMinors.onclick = () => load('minors');

        load();

        function load(filter){

            let endpoint = listEndpoint;

            if(filter){
                endpoint += "?filter="+filter;
            }

            fetch(endpoint)
            .then(function(response){
                if (response.ok) {
                    response.json().then(function (data) {
                        if(data.length){
                            defaultText.style.display = 'none';
                            filters.style.display = 'block';
                            peopleTable.style.display = 'table';
                            peopleList.innerHTML = '';
                            data.forEach((person) => addPerson(person));
                        }
                    });
                } else {
                    showAlert("danger","Error cargando los datos");
                }
            })
            .catch(function(error){
                showAlert("danger","Error cargando los datos");
            });
        }

        function save(){
            //Limpieza de estados anteriores
            let inputs = document.getElementsByClassName('form-control');
            let errorContainers = document.getElementsByClassName('invalid-feedback');
            for(let input of inputs){
                input.classList.remove('is-invalid');
            }
            for(let container of errorContainers){
                container.style.display = 'none'
            }

            const response = fetch('{{route('savePerson')}}', {
                method: "POST",
                body: JSON.stringify({ name:  nameInput.value, lastName: lastNameInput.value, birthDate: birthDateInput.value}),
            }).then(function(response){
                if (response.status == 200) {
                    //Escrito correctamente, limpia el formulario y recarga
                    for(let input of inputs){
                        input.value = '';
                    }
                    load();
                    showAlert("success","Persona registrada.");
                } else if(response.status == 400) {
                    //Fallo por validación
                    response.json().then(function (data) {
                        Object.keys(data).forEach((key) => {
                            let inputElement = document.getElementById(key+"Input");
                            let errorContainer = document.getElementById(key+"Errors");
                            inputElement.classList.add('is-invalid');
                            errorContainer.style.display = 'block';
                            errorContainer.innerText = data[key][0];
                        });
                    });
                    showAlert("danger","Los campos no son correctos. Revisa los errores.");
                } else {
                    showAlert("danger","Se ha producido un error.");
                }
            })
            .catch(function(error){
                showAlert("danger","Se ha producido un error.");
            });
        }

        function showAlert(level, text){
            alertBanner.className = "";
            alertBanner.classList.add('alert');
            alertBanner.classList.add('alert-'+level);
            alertBanner.innerText = text;
            alertBanner.style.display = 'block';
        }

        function addPerson(person){
            let row = document.createElement('tr');
            row.innerHTML = "<td>"+person.id+"</td>"
                + "<td>"+person.name+"</td>"
                + "<td>"+person.last_name+"</td>"
                + "<td>"+person.birth_date+"</td>";
            row.id = 'rowPerson'+person.id;
            let actionCell = document.createElement('td');
            let deleteButton = document.createElement('button');
            deleteButton.innerText = 'Borrar';
            deleteButton.classList.add('btn');
            deleteButton.classList.add('btn-danger');
            deleteButton.onclick = () => deletePerson(person.id);
            actionCell.append(deleteButton);
            row.append(actionCell);
            peopleList.append(row);
        }

        function deletePerson(id){
            if(confirm('¿Deseas borrar la persona?')){
                fetch(deleteEndpoint.replace('placeholder',id),{
                    method : 'DELETE'
                }).then(function(response){
                    if (response.ok) {
                        let row = document.getElementById('rowPerson'+id);
                        row.remove();
                        showAlert("success","Persona eliminada");
                    } else {
                        showAlert("danger","Error al borrar");
                    }
                })
                .catch(function(error){
                    showAlert("danger","Error al borrar");
                });
            };
        }
    </script>
</body>
</html>