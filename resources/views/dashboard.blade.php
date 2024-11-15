<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                    <hr class=py-5>
                    <button id="runSuccessJob"
                        class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-50 transition duration-200 ease-in-out shadow-lg">
                        Run Success Job
                    </button>

                    <button id="runFailureJob"
                        class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50 transition duration-200 ease-in-out shadow-lg ml-4">
                        Run Failure Job
                    </button>

                    <button id="startWorker"
                        class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition duration-200 ease-in-out shadow-lg ml-4">
                        Start Worker
                    </button>

                
                </div>
            </div>
        </div>

    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 "> 
            <div id="jobResults"
                class="mt-4 p-4 bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-lg shadow-md mx-auto text-center bg-white">
                <h3 class="text-lg font-semibold mb-2">Resultados:</h3>
                <p id="jobMessage"></p>
            </div>
        </div>
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Logs de Trabajos</h3>
                    <div class="table-responsive">
                        <table id="logsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Nivel</th>
                                    <th>Mensaje</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    // Función para mostrar mensajes en el área de resultados
    function displayMessage(message, type = 'success') {
        let colorClass = type === 'success' ? 'text-green-500' : 'text-red-500';
        $('#jobMessage').prepend(`<hr><p class="${colorClass} font-bold">${message}</p>`);
    }

    // Configuración de AJAX para incluir el token CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showLoading(button) {
        $(button).prop('disabled', true);
        $(button).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
    }

    function hideLoading(button, originalText) {
        $(button).prop('disabled', false);
        $(button).html(originalText);
    }

    $('#runSuccessJob').on('click', function() {
        let originalText = $(this).html();
        showLoading(this); // Show loading state

        $.ajax({
            url: 'test-job-success',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                displayMessage(data.message, 'success');
                $('#logsTable').DataTable().ajax.reload();
                hideLoading($('#runSuccessJob'), originalText); // Hide loading state
            },
            error: function(xhr, status, error) {
                displayMessage('Error: ' + error, 'error');
                $('#logsTable').DataTable().ajax.reload();
                hideLoading($('#runSuccessJob'), originalText); // Hide loading state
            }
        });
    });

    $('#runFailureJob').on('click', function() {
        let originalText = $(this).html();
        showLoading(this); // Show loading state

        $.ajax({
            url: 'test-job-failure',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                hideLoading($('#runFailureJob'), originalText); // Hide loading state
                displayMessage(data.message, 'error');
                if (data.error) {
                    displayMessage('Error: ' + data.error, 'error');
                }
                $('#logsTable').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                hideLoading($('#runFailureJob'), originalText); // Hide loading state
                displayMessage('Error: ' + error, 'error');
                $('#logsTable').DataTable().ajax.reload();
            }
        });
    });

    $('#startWorker').on('click', function() {
        let originalText = $(this).html();
        showLoading(this); // Show loading state

        $.ajax({
            url: 'start-worker',
            method: 'POST',
            success: function(data) {
                hideLoading($('#startWorker'), originalText); // Hide loading state
                displayMessage('Worker started successfully', 'success');
                $('#logsTable').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                hideLoading($('#startWorker'), originalText); // Hide loading state
                displayMessage('Error starting worker: ' + error, 'error');
                $('#logsTable').DataTable().ajax.reload();
            }
        });
    });

    $('#stopWorker').on('click', function() {
        let originalText = $(this).html();
        showLoading(this); // Show loading state

        $.ajax({
            url: 'stop-worker',
            method: 'POST',
            success: function(data) {
                hideLoading($('#stopWorker'), originalText); // Hide loading state
                displayMessage('Worker stopped successfully', 'success');
                $('#logsTable').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                hideLoading($('#stopWorker'), originalText); // Hide loading state
                displayMessage('Error stopping worker: ' + error, 'error');
                $('#logsTable').DataTable().ajax.reload();
            }
        });
    });

    $(document).ready(function() {
        $('#logsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route('logs.get') }}',
                type: 'GET',
                dataSrc: '',
            },
            columns: [{
                    data: 'date',
                    title: 'Fecha'
                },
                {
                    data: 'level',
                    title: 'Nivel'
                },
                {
                    data: 'message',
                    title: 'Mensaje'
                }
            ],
            order: [
                [0, 'desc']
            ]
        });
    });
</script>
