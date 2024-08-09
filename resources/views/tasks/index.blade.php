<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Energeek</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center mb-3">
            <img src="assets/img/image.png" alt="energeek" style="width: 252px; height: 60px">
        </div>
        <div class="row justify-content-center">
            <!-- Menggabungkan form pengguna dan to do list -->
            <form action="/task" method="POST" id="mainForm">
                @csrf
                <!-- Bagian Users -->
                <div class="row align-items-center">
                    <div class="col-4">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nama" />
                    </div>
                    <div class="col-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username"
                            placeholder="Username" />
                    </div>
                    <div class="col-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
                    </div>
                </div>

                <!-- Bagian To Do List -->
                <div class="container mt-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col">
                            <h3>To Do List</h3>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <button type="button" id="tambahTodo" class="btn btn-outline-danger">
                                <i class="fa-solid fa-plus"></i> Tambah To Do
                            </button>
                        </div>
                    </div>

                    <div id="todoList">
                        <div class="row align-items-center mt-2" id="ToDo">
                            <div class="col-9">
                                <label for="list" class="form-label">Judul To Do</label>
                                <input type="text" class="form-control" name="list[]"
                                    placeholder="Contoh: Perbaikan api master" />
                            </div>
                            <div class="col-2">
                                <label for="categories" class="form-label">Kategori</label>
                                <select name="categories[]" class="form-select">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1 mt-4">
                                <button type="button" class="btn btn-danger removeTodo">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar To-Do List Ditampilkan Di Sini -->
                    <div id="todoListDisplay" class="mt-4">
                        <!-- Data to-do list akan ditampilkan di sini -->
                    </div>

                    <div class="row mt-3">
                        <button type="submit" class="btn btn-success">SIMPAN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            $("#name").on('blur', function() {
                var name = $(this).val();

                if (name) {
                    $.ajax({
                        url: '/check-user', // Endpoint untuk memeriksa keberadaan pengguna
                        method: 'POST',
                        data: {
                            name: name
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#username').val(response.user.username);
                                $('#email').val(response.user.email);
                                fetchTodoList(response.user.id);
                            } else {
                                $('#username').val('');
                                $('#email').val('');
                                displayTodoList();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }
            });

            $("#tambahTodo").click(function() {
                var newToDo = $("#ToDo").first().clone();
                newToDo.find("input").val('');
                $("#todoList").append(newToDo.hide().slideDown('slow'));
            });

            $(document).on('click', '.removeTodo', function() {
                $(this).closest('#ToDo').slideUp('slow', function() {
                    $(this).remove();
                });
            });

            $("#mainForm").submit(function(event) {
                event.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize();

                $.ajax({
                    url: "/task", // Ganti dengan URL endpoint di backend
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert("Data berhasil disimpan!");
                        $('#mainForm')[0].reset();
                        displayTodoList();
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan:', error);
                        alert("Terjadi kesalahan, data gagal disimpan.");
                    }
                });
            });

            function fetchTodoList(userId) {
                $.ajax({
                    url: '/fetch-todo-list', // Endpoint untuk mengambil daftar to-do list
                    method: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(data) {
                        var todoListHtml = '';
                        $.each(data.tasks, function(index, task) {
                            todoListHtml += '<div class="row align-items-center mt-2">';
                            todoListHtml += '<div class="col-9">';
                            todoListHtml += '<label class="form-label">Judul To Do</label>';
                            todoListHtml += '<input type="text" class="form-control" value="' +
                                task.description + '" readonly />';
                            todoListHtml += '</div>';
                            todoListHtml += '<div class="col-2">';
                            todoListHtml += '<label class="form-label">Kategori</label>';
                            todoListHtml += '<select class="form-select" disabled>';
                            $.each(data.categories, function(i, category) {
                                todoListHtml += '<option value="' + category.id + '"' +
                                    (category.id == task.category_id ? ' selected' :
                                        '') + '>' + category.name + '</option>';
                            });
                            todoListHtml += '</select>';
                            todoListHtml += '</div>';
                            todoListHtml += '<div class="col-1 mt-4">';
                            todoListHtml +=
                                '<button type="button" class="btn btn-danger removeTodo">';
                            todoListHtml += '<i class="fa-solid fa-trash"></i>';
                            todoListHtml += '</button>';
                            todoListHtml += '</div>';
                            todoListHtml += '</div>';
                        });
                        $('#todoListDisplay').html(todoListHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan:', error);
                    }
                });
            }

            function displayTodoList() {
                $.ajax({
                    url: "/display-todo-list", // Ganti dengan URL endpoint Anda
                    method: 'GET',
                    success: function(data) {
                        var todoListHtml = '';
                        $.each(data.tasks, function(index, task) {
                            todoListHtml += '<div class="row align-items-center mt-2">';
                            todoListHtml += '<div class="col-9">';
                            todoListHtml += '<label class="form-label">Judul To Do</label>';
                            todoListHtml += '<input type="text" class="form-control" value="' +
                                task.description + '" readonly />';
                            todoListHtml += '</div>';
                            todoListHtml += '<div class="col-2">';
                            todoListHtml += '<label class="form-label">Kategori</label>';
                            todoListHtml += '<select class="form-select" disabled>';
                            $.each(data.categories, function(i, category) {
                                todoListHtml += '<option value="' + category.id + '"' +
                                    (category.id == task.category_id ? ' selected' :
                                        '') + '>' + category.name + '</option>';
                            });
                            todoListHtml += '</select>';
                            todoListHtml += '</div>';
                            todoListHtml += '<div class="col-1 mt-4">';
                            todoListHtml +=
                                '<button type="button" class="btn btn-danger removeTodo">';
                            todoListHtml += '<i class="fa-solid fa-trash"></i>';
                            todoListHtml += '</button>';
                            todoListHtml += '</div>';
                            todoListHtml += '</div>';
                        });
                        $('#todoListDisplay').html(todoListHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan:', error);
                    }
                });
            }

            // Panggil fetchTodoList saat halaman dimuat
            displayTodoList();
        });
    </script>
</body>

</html>
