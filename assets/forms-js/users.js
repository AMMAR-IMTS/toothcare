
$(document).ready(function () {
    function validatePasswords() {
        if ($('#passwordInput').val() === $('#confirmPasswordInput').val()) {
            return true;
        } else {
            return false;
        }
    }

    $('#create').on('click', function () {
        var form = $('#create-form')[0] ?? null;
        if (!form) console.log('Something went wrong..');

        if (!validatePasswords()) {
            showAlert('Passwords do not match..!', 'danger'); // Prevent form submission if passwords do not match
            return;
        }

        var url = $('#create-form').attr('action');
        if (form.checkValidity() && form.reportValidity()) {
            var formData = new FormData(form);
            // Perform AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false, // Don't set content type
                processData: false, // Don't process the data
                dataType: 'json',
                success: function (response) {
                    showAlert(response.message, response.success ? 'primary' : 'danger');
                    if (response.success) {
                        $('#createUserModal').modal('hide');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function (error) {
                    // Handle the error
                    console.error('Error submitting the form:', error);
                    showAlert('Something went wrong..!', 'danger');
                },
                complete: function (response) {
                    // This will be executed regardless of success or error
                    console.log('Request complete:', response);

                }
            });


        } else {
            showAlert('Form is not valid. Please check your inputs.', 'danger');
        }
    });
});

$(document).ready(function () {

    $('.edit-user').on('click', async function () {
        var user_id = $(this).data('id');
        await getUserById(user_id);
    })

    $('.delete-user').on('click', async function () {
        var user_id = $(this).data('id');
        var is_confirm = confirm('Are you sure,Do you want to delete?');
        if (is_confirm) await deleteById(user_id);
    })

    $('#update-now').on('click', function () {

        // Get the form element
        var form = $('#update-user-form')[0];
        $('#update-user-form')[0].reportValidity();

        // Check form validity
        if (form.checkValidity()) {
            // Serialize the form data
            var formAction = $('#update-user-form').attr('action');
            var formData = new FormData($('#update-user-form')[0]);

            // Perform AJAX request
            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData, // Form data
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (response) {
                    showAlert(response.message, response.success ? 'primary' : 'danger', 'alert-container-update-form');
                    if (response.success) {
                        $('#editUserModal').modal('hide');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function (error) {
                    // Handle the error
                    console.error('Error submitting the form:', error);
                },
                complete: function (response) {
                    // This will be executed regardless of success or error
                    console.log('Request complete:', response);
                }
            });
        } else {
            var message = ('Form is not valid. Please check your inputs.');
            showAlert(message, 'danger');
        }
    });

    $('#permission').change(function () {
        var permission = $(this).val();
        if (permission === 'doctor') {
            $('#additional-fields').html(
                '<div class="row mt-2">' +
                '<div class="col-12 mb-3">' +
                '<label for="name" class="form-label">Doctor Name</label>' +
                '<input type="text" id="name" name="doctor_name" class="form-control" placeholder="Enter Name" required />' +
                '</div>' +
                '<div class="col-12 mb-3">' +
                '<label for="about" class="form-label">About Doctor</label>' +
                '<textarea id="about" name="about_doctor" class="form-control" placeholder="Enter About" required></textarea>' +
                '</div>' +
                '<div class="col-12 mb-3">' +
                '<label for="formFile" class="form-label">Doctor Photo</label>' +
                '<input class="form-control" name="image" id="image" type="file" accept="image/*">' +
                '</div>' +
                '</div>'
            );
        } else {
            $('#additional-fields').empty();
        }
    });

    // Trigger change event on page load if doctor permission is selected by default
    if ($('#permission, #edit_permission').val() === 'doctor') {
        $('#permission, #edit_permission').trigger('change');
    }

    $('#edit_permission').change(function () {
        var permission = $(this).val();
        if (permission === 'doctor') {
            $('#edit-additional-fields').html(
                '<div class="row mt-2">' +
                '<div class="col-12 mb-3">' +
                '<label for="name" class="form-label">Doctor Name</label>' +
                '<input type="text" id="edit_name" name="doctor_name" class="form-control" placeholder="Enter Name" required />' +
                '</div>' +
                '<div class="col-12 mb-3">' +
                '<label for="about" class="form-label">About Doctor</label>' +
                '<textarea id="edit_about" name="about_doctor" class="form-control" placeholder="Enter About" required></textarea>' +
                '</div>' +
                '<div class="col-12 mb-3">' +
                '<label for="formFile" class="form-label">Doctor Photo</label>' +
                '<input class="form-control" name="image" id="edit_image" type="file" accept="image/*">' +
                '</div>' +
                '</div>'
            );
        } else {
            $('#edit-additional-fields').empty();
        }
    });
});

async function getUserById(id) {
    var formAction = $('#update-user-form').attr('action');

    // Perform AJAX request
    $.ajax({
        url: formAction,
        type: 'GET',
        data: {
            user_id: id,
            action: 'get_user'
        }, // Form data
        dataType: 'json',
        success: function (response) {
            showAlert(response.message, response.success ? 'primary' : 'danger');
            if (response.success) {
                var user_id = response.data.id;
                var username = response.data.username;
                var email = response.data.email;
                var permission = response.data.permission;
                var is_active = response.data.is_active;

                $('#editUserModal #user_id').val(user_id);
                $('#editUserModal #username').val(username);
                $('#editUserModal #email').val(email);
                $('#editUserModal #permission option[value="' + permission + '"]').prop('selected', true);
                $('#editUserModal #is_active option[value="' + is_active + '"]').prop('selected', true);
                $('#editUserModal').modal('show');
            }
        },
        error: function (error) {
            // Handle the error
            console.error('Error submitting the form:', error);
        },
        complete: function (response) {
            // This will be executed regardless of success or error
            console.log('Request complete:', response);
        }
    });
}

async function deleteById(id) {
    var formAction = $('#update-user-form').attr('action');

    // Perform AJAX request
    $.ajax({
        url: formAction,
        type: 'GET',
        data: {
            user_id: id,
            action: 'delete_user'
        }, // Form data
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        },
        error: function (error) {
            // Handle the error
            console.error('Error submitting the form:', error);
        },
        complete: function (response) {
            // This will be executed regardless of success or error
            console.log('Request complete:', response);
        }
    });
}