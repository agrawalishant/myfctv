// Login Page
$(function () {
    $("#login_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#login_submit').attr('disabled', true); // disable the login button
                $('#login_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // clear the invalid credential error message if any input field is changed
                    $('#login_form input').on('change', function () {
                        $('#login_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 2) {
                    $('#login_error').text('Invalid login credentials. Please try again.');
                } else {
                    window.location.href = '/dashboard';

                }
            },
            error: function () {
                $('#login_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#login_submit').attr('disabled', false); // enable the login button
                $('#login_submit').html('Sign In'); // remove spinner from button
            }
        });
    });
});

// Reset Password Page
$(function () {
    $('#forgot-password-form').on('submit', function (e) {
        e.preventDefault();
        var form_data = new FormData(this);

        // Clear old error message
        $('#email_error').text('');

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: form_data,
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $('#email_submit').attr('disabled', true); // disable the login button
                $('#email_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.error && data.error.message) {
                    $('#email_error').text(data.error.message);
                } else if (data.status == 0) {
                    $('#email_error').text('Email does not exist in the database.');
                } else {
                    window.location.href = '/confirm-mail';
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
                $('#email_error').text('An error occurred. Please try again later.');
            },
            complete: function () {
                $('#email_submit').attr('disabled', false); // enable email button
                $('#email_submit').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M3 7l9 6l9 -6" /></svg>Send me reset link'); // remove spinner from button
            }
        });
    });
});


// Reset Password
$(document).ready(function () {
    $('#reset_password_form').submit(function (e) {
        e.preventDefault();

        var password = $('#password').val();
        var passwordConfirmation = $('#password_confirmation').val();
        var email = $('input[name="email"]').val();
        var token = $('input[name="token"]').val();

        // Validate the password fields
        if (password.length < 6) {
            $('.password_error').text('Password should be at least 6 characters long');
            return;
        } else {
            $('.password_error').text('');
        }

        if (password !== passwordConfirmation) {
            $('.password_confirmation_error').text('Password and confirm password must match');
            return;
        } else {
            $('.password_confirmation_error').text('');
        }

        // Create a FormData object and append the form data
        var formData = new FormData(this);
        formData.append('email', email);
        formData.append('token', token);

        // Perform AJAX request
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {

                // Redirect to the login page
                window.location.href = '/system-login';
            },
            error: function (xhr, status, error) {
                // Password reset failed
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error && xhr.responseJSON.error.message ? xhr.responseJSON.error.message : 'Password reset failed';
                Swal.fire({
                    icon: 'error',
                    title: 'Password Reset',
                    text: errorMessage,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });
});

// Update User Status
$(document).on("click", ".updateUserStatus", function () {
    var status = $(this).text();
    var user_id = $(this).attr("user_id");
    var $userElement = $("#user-" + user_id);

    $.ajax({
        type: 'post',
        url: '/dashboard/update-user-status',
        data: { status: status, user_id: user_id },
        success: function (resp) {
            if (resp['status'] == 0) {
                $userElement.find('span').removeClass('bg-success').addClass('bg-primary').text('Inactive');
            } else if (resp['status'] == 1) {
                $userElement.find('span').removeClass('bg-primary').addClass('bg-success').text('Active');
            }
        }, error: function () {
            alert("Error");
        }
    });
});


// Delete User
$(document).on("click", ".user-delete-btn", function (e) {
    e.preventDefault();
    var userId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete user
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-user/' + userId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted user from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'User has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete user.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete user.', 'error');
                }
            });
        }
    });
});

// Add Video Category
$(document).ready(function () {
    $('#addCategoryForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            success: function (data) {
                if (data.status == 1) {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Category added successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            $('#addCategoryForm')[0].reset();
                        }
                    });
                } else {
                    // Display error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to add category. Please check your input.',
                    });
                }
            },
            error: function () {
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing the request.',
                });
            }
        });
    });
});

// Edit Video Category
$(document).ready(function () {
    $('#editCategoryForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            success: function (data) {
                if (data.status == 1) {
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Category updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {

                        }
                    });
                } else {
                    // Display error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update category. Please check your input.',
                    });
                }
            },
            error: function () {
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing the request.',
                });
            }
        });
    });
});


// Delete Movie Category
$(document).on("click", ".mcat-delete-btn", function (e) {
    e.preventDefault();
    var mcatId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete movie category
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-mcat/' + mcatId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.status) {
                        // Update content or remove the deleted movie category from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Movie category has been deleted.', 'success');
                    } else {
                        if (response.message.includes('linked movies')) {
                            Swal.fire('Error!', response.message, 'warning');
                        } else {
                            Swal.fire('Error!', 'Failed to delete Movie category.', 'error');
                        }
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Movie category.', 'error');
                }
            });
        }
    });
});

// Add Movie Details
$(function () {
    $("#movie_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#movie_submit').attr('disabled', true); // disable the submit button
                $('#movie_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#movie_form input').on('change', function () {
                        $('#movie_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Movie details added successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.href = '/dashboard/movie/list';
                        }
                    });
                    // Perform any necessary actions after successful lead submission
                } else {
                    toastr.error('Failed to save Movie. Please try again.');
                }
            },
            error: function (xhr) {
                $('#movie_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#movie_submit').attr('disabled', false); // enable the submit button
                $('#movie_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Edit Movie Details
$(function () {
    $(".edit_movie_form").on('submit', function (e) {
        e.preventDefault();

        var form = $(this);

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                form.find('span.error-text').text('');
                form.find('#edit_movie_submit').attr('disabled', true); // disable the submit button
                form.find('#edit_movie_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    form.find('input').on('change', function () {
                        form.find('#edit_movie_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        form.find('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Movie details updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.href = '/dashboard/movie/list';
                        }
                    });
                    // Perform any necessary actions after successful lead submission
                } else {
                    toastr.error('Failed to save Movie. Please try again.');
                }
            },
            error: function (xhr) {
                form.find('#edit_movie_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                form.find('#edit_movie_submit').attr('disabled', false); // enable the submit button
                form.find('#edit_movie_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Delete Movie Details
$(document).on("click", ".movie-delete-btn", function (e) {
    e.preventDefault();
    var movieId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete movie
            $.ajax({
                type: 'GET',
                url: '/dashboard/movie-delete/' + movieId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted movie from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Movie has been deleted.', 'success');
                    } else {
                        if (response.message.includes('linked video versions')) {
                            Swal.fire('Error!', response.message, 'warning');
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Movie.', 'error');
                }
            });
        }
    });
});


// Add Cast Details
$(function () {
    $("#castCrew").on('submit', function (e) {
        e.preventDefault();

        var form = $(this);
        var formData = new FormData(form[0]); // Use the form directly

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                form.find('span.error-text').text('');
                $('#cast_submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    form.find('input').on('change', function () {
                        form.find('.error-text').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        form.find('span.' + prefix + '_error').text(val[0]);
                    });
                } else if (data.status == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cast details added successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful cast submission
                } else {
                    toastr.error('Failed to save Cast. Please try again.');
                }
            },
            error: function (xhr) {
                form.find('#movie_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#cast_submit').prop('disabled', false).html('Submit');
            }
        });
    });
});


// Delete Cast
$(document).on("click", ".cmem-delete-btn", function (e) {
    e.preventDefault();
    var cmemId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete Cast/Crew member
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-cmem/' + cmemId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted Cast/Crew member from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Cast/Crew member has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete Cast/Crew member.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Cast/Crew member.', 'error');
                }
            });
        }
    });
});


// Delete Video
$(document).on("click", ".vid-delete-btn", function (e) {
    e.preventDefault();
    var vidId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete video
            $.ajax({
                type: 'GET',
                url: '/dashboard/video-delete/' + vidId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted video from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Video has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete video.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete video.', 'error');
                }
            });
        }
    });
});

// Add Series
$(function () {
    $("#series_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#series_submit').attr('disabled', true); // disable the submit button
                $('#series_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#series_form input').on('change', function () {
                        $('#series_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Series created successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.href = '/dashboard/series/list';
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else {
                    toastr.error('Failed to save Series. Please try again.');
                }
            },
            error: function (xhr) {
                $('#series_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#series_submit').attr('disabled', false); // enable the submit button
                $('#series_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Edit Series
$(function () {
    $("#edit_series_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#edit_series_submit').attr('disabled', true); // disable the submit button
                $('#edit_series_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#edit_series_form input').on('change', function () {
                        $('#edit_series_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Series updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else {
                    toastr.error('Failed to save Series. Please try again.');
                }
            },
            error: function (xhr) {
                $('#edit_series_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#edit_series_submit').attr('disabled', false); // enable the submit button
                $('#edit_series_submit').html('Update'); // remove spinner from button
            }
        });
    });
});

// Add Season
$(function () {
    $("#season_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#season_submit').attr('disabled', true); // disable the submit button
                $('#season_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#season_form input').on('change', function () {
                        $('#season_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Season Created successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else {
                    toastr.error('Failed to save Season. Please try again.');
                }
            },
            complete: function () {
                $('#season_submit').attr('disabled', false); // enable the submit button
                $('#season_submit').html('Create Season'); // remove spinner from button
            }
        });
    });
});

// Add series Cast Details
$(function () {
    $("#member_form").on('submit', function (e) {
        e.preventDefault();

        var form = $(this);
        var formData = new FormData(form[0]); // Use the form directly

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                form.find('span.error-text').text('');
                $('#memeber_submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    form.find('input').on('change', function () {
                        form.find('.error-text').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        form.find('span.' + prefix + '_error').text(val[0]);
                    });
                } else if (data.status == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cast details added successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful cast submission
                } else {
                    toastr.error('Failed to save Cast. Please try again.');
                }
            },
            error: function (xhr) {
                form.find('#member_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#cast_submit').prop('disabled', false).html('Submit');
            }
        });
    });
});


// Delete Series Cast
$(document).on("click", ".mem-delete-btn", function (e) {
    e.preventDefault();
    var memId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete Cast/Crew member
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-mem/' + memId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted Cast/Crew member from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Cast/Crew member has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete Cast/Crew member.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Cast/Crew member.', 'error');
                }
            });
        }
    });
});

// Delete Episode
$(document).on("click", ".epi-delete-btn", function (e) {
    e.preventDefault();
    var epiId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete episode
            $.ajax({
                type: 'GET',
                url: '/dashboard/series/season/delete-episode/' + epiId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted episode from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Episode has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete Episode.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Episode.', 'error');
                }
            });
        }
    });
});

// Delete Season
$(document).on("click", ".season-delete-btn", function (e) {
    e.preventDefault();
    var seasonId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete movie category
            $.ajax({
                type: 'GET',
                url: '/dashboard/series/delete-season/' + seasonId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.status) {
                        // Update content or remove the deleted movie category from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Season has been deleted.', 'success');
                    } else {
                        if (response.message.includes('linked episode')) {
                            Swal.fire('Error!', response.message, 'warning');
                        } else {
                            Swal.fire('Error!', 'Failed to delete Season.', 'error');
                        }
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Season.', 'error');
                }
            });
        }
    });
});

// Delete Series
$(document).on("click", ".series-delete-btn", function (e) {
    e.preventDefault();
    var seriesId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete series
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-series/' + seriesId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted movie from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Series has been deleted.', 'success');
                    } else {
                        if (response.message.includes('linked season or cast')) {
                            Swal.fire('Error!', response.message, 'warning');
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Series.', 'error');
                }
            });
        }
    });
});


// Add Slider
$(function () {
    $("#slider_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#slider_submit').attr('disabled', true); // disable the submit button
                $('#slider_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#slider_form input').on('change', function () {
                        $('#slider_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Slider added successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful slider submission
                } else {
                    toastr.error('Failed to save Slider. Please try again.');
                }
            },
            error: function (xhr) {
                $('#slider_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#slider_submit').attr('disabled', false); // enable the submit button
                $('#slider_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Edit Slider
$(function () {
    $(".edit_slider_form").on('submit', function (e) {
        e.preventDefault();

        var form = $(this);

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                form.find('span.error-text').text('');
                form.find('#edit_slider_submit').attr('disabled', true); // disable the submit button
                form.find('#edit_slider_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    form.find('input').on('change', function () {
                        form.find('#edit_slider_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        form.find('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Slider updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful slider submission
                } else {
                    toastr.error('Failed to update Slider. Please try again.');
                }
            },
            error: function (xhr) {
                form.find('#edit_slider_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                form.find$('#edit_slider_submit').attr('disabled', false); // enable the submit button
                form.find('#edit_slider_submit').html('Update'); // remove spinner from button
            }
        });
    });
});

// Delete Slider
$(document).on("click", ".slider-delete-btn", function (e) {
    e.preventDefault();
    var sliderId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete slider
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-slider/' + sliderId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.status) {
                        // Update content or remove the deleted slider from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Slider has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete Slider. ' + response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error!', 'Failed to delete Slider. Check console for details.', 'error');
                }
            });
        }
    });
});

// Add Subscription Plan
$(function () {
    $("#subscription_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#subscription_submit').attr('disabled', true); // disable the submit button
                $('#subscription_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#subscription_form input').on('change', function () {
                        $('#subscription_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Subscription Plan Added Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful subscription plan submission
                } else {
                    toastr.error('Failed to save Subscription Plan. Please try again.');
                }
            },
            error: function (xhr) {
                $('#subscription_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#subscription_submit').attr('disabled', false); // enable the submit button
                $('#subscription_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Edit Subscription Plan
$(function () {
    $(".edit_subscription_form").on('submit', function (e) {
        e.preventDefault();

        var form = $(this);
        // var subscriptionId = form.attr('id').split('_')[3];
        // alert("Form ID: " + form.attr('id') + ", Subscription ID: " + subscriptionId);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: new FormData(form[0]),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                form.find('span.error-text').text('');
                form.find('#edit_subscription_submit').attr('disabled', true);
                form.find('#edit_subscription_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            },
            success: function (data) {
                if (data.status == 0) {
                    form.find('input').on('change', function () {
                        form.find('#edit_subscription_error').text('');
                    });

                    $.each(data.error, function (prefix, val) {
                        form.find('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Subscription Plan Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    toastr.error('Failed to update Subscription Plan. Please try again.');
                }
            },
            error: function (xhr) {
                form.find('#edit_subscription_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                form.find('#edit_subscription_submit').attr('disabled', false);
                form.find('#edit_subscription_submit').html('Update');
            }
        });
    });
});

// Delete Subscription Plan
$(document).on("click", ".subscription-delete-btn", function (e) {
    e.preventDefault();
    var subscriptionId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete slider
            $.ajax({
                type: 'GET',
                url: '/dashboard/subscription-plan/delete/' + subscriptionId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.status) {
                        // Update content or remove the deleted subscription from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'subscription has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete subscription. ' + response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error!', 'Failed to delete subscription. Check console for details.', 'error');
                }
            });
        }
    });
});

// SMTP
$(function () {
    $("#smtp_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#smtp_submit').attr('disabled', true); // disable the submit button
                $('#smtp_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#smtp_form input').on('change', function () {
                        $('#smtp_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'SMTP Added Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful SMTP submission
                } else {
                    toastr.error('Failed to save SMTP. Please try again.');
                }
            },
            complete: function () {
                $('#smtp_submit').attr('disabled', false); // enable the submit button
                $('#smtp_submit').html('Submit'); // remove spinner from button
            }
        });
    });
});

// Check Current Password
$(document).ready(function () {
    //Check Admin Password is correct or not
    $("#current_pwd").keyup(function () {
        var current_pwd = $("#current_pwd").val();
        //alert(current_pwd);
        $.ajax({
            type: 'post',
            url: '/dashboard/check-current-pwd',
            data: { current_pwd: current_pwd },
            success: function (resp) {
                if (resp == "false") {
                    $("#chkCurrentPwd").html("<font color=red> Current Password is incorrect </font>");
                } else if (resp == "true") {
                    $("#chkCurrentPwd").html("<font color=green> Current Password is correct </font>");
                }
            }, error: function () {
                alert("Error");
            }
        });
    });
});

// Update Admin Password
$(function () {
    $("#updatePasswordForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#password_submit').attr('disabled', true); // disable the submit button
                $('#password_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#updatePasswordForm input').on('change', function () {
                        $('#password_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Password updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else if (data.status == 2) {
                    Swal.fire({
                        icon: 'question',
                        title: 'New Password & Confirm Password Not Matched !!',
                        showConfirmButton: false,
                        timer: 1500,

                    });
                } else if (data.status == 3) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Your Current Password is Incorrect !!',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    toastr.error('Failed to update password. Please try again.');
                }
            },
            error: function (xhr) {
                $('#password_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#password_submit').attr('disabled', false); // enable the submit button
                $('#password_submit').html('Update Password'); // remove spinner from button
            }
        });
    });
});

// Update Admin Profile
$(function () {
    $("#updateProfileForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#profile_submit').attr('disabled', true); // disable the submit button
                $('#profile_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#updateProfileForm input').on('change', function () {
                        $('#password_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Profile updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else {
                    toastr.error('Failed to update password. Please try again.');
                }
            },
            error: function (xhr) {
                $('#profile_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#profile_submit').attr('disabled', false); // enable the submit button
                $('#profile_submit').html('Update Profile'); // remove spinner from button
            }
        });
    });
});

// Add Role
$(function () {
    $("#roleForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#role_submit').attr('disabled', true); // disable the submit button
                $('#role_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    Swal.fire({
                        icon: 'warning',
                        title: 'Role with this name already exists.',
                        showConfirmButton: false,
                        timer: 1500,
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Role Added Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful role submission
                } else {
                    toastr.error('Failed to save role . Please try again.');
                }
            },
            error: function (xhr) {
                $('#role_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#role_submit').attr('disabled', false); // enable the submit button
                $('#role_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Update Role Status
$(document).on("click", ".updateRoleStatus", function () {
    var status = $(this).text();
    var role_id = $(this).attr("role_id");
    var $roleElement = $("#role-" + role_id);

    $.ajax({
        type: 'post',
        url: '/dashboard/update-role-status',
        data: { status: status, role_id: role_id },
        success: function (resp) {
            if (resp['status'] == 0) {
                $roleElement.find('span').removeClass('bg-success').addClass('bg-primary').text('Inactive');
            } else if (resp['status'] == 1) {
                $roleElement.find('span').removeClass('bg-primary').addClass('bg-success').text('Active');
            }
        }, error: function () {
            alert("Error");
        }
    });
});

// Delete Role
$(document).on("click", ".r-delete-btn", function (e) {
    e.preventDefault();
    var rId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete role
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-role/' + rId,
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE' // Use DELETE method for a RESTful delete action
                },
                success: function (response) {
                    if (response.status === 1) {
                        // Update content or remove the deleted role from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Role has been deleted.', 'success');
                    } else {
                        if (response.message.includes('linked admin')) {
                            Swal.fire('Error!', response.message, 'warning');
                        } else {
                            Swal.fire('Error!', 'Failed to delete Role.', 'error');
                        }
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete Role.', 'error');
                }
            });
        }
    });
});

// Add Admin
$(function () {
    $("#adminForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#adminSubmit').attr('disabled', true); // disable the submit button
                $('#adminSubmit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    // Clear the validation error messages if any input field is changed
                    $('#adminForm input').on('change', function () {
                        $('#admin_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Admin Added Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful role submission
                } else if (data.status == 2) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'This email is already associated with another account.',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    toastr.error('Failed to save role . Please try again.');
                }
            },
            error: function (xhr) {
                $('#role_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#adminSubmit').attr('disabled', false); // enable the submit button
                $('#adminSubmit').html('Submit'); // remove spinner from button
            }
        });
    });
});

// Update Admin Status
$(document).on("click", ".updateAdminStatus", function () {
    var status = $(this).text();
    var admin_id = $(this).attr("admin_id");
    var $adminElement = $("#admin-" + admin_id);

    $.ajax({
        type: 'post',
        url: '/dashboard/update-admin-status',
        data: { status: status, admin_id: admin_id },
        success: function (resp) {
            if (resp['status'] == 0) {
                $adminElement.find('span').removeClass('bg-success').addClass('bg-primary').text('Inactive');
            } else if (resp['status'] == 1) {
                $adminElement.find('span').removeClass('bg-primary').addClass('bg-success').text('Active');
            }
        }, error: function () {
            alert("Error");
        }
    });
});

// Edit Admin Details
$(function () {
    $("#editForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#adminUpdate').attr('disabled', true); // disable the submit button
                $('#adminUpdate').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    // Clear the validation error messages if any input field is changed
                    $('#editForm input').on('change', function () {
                        $('#edit_admin_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Admin Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful role submission
                } else if (data.status == 2) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'This email is already associated with another account.',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    toastr.error('Failed to save role . Please try again.');
                }
            },
            error: function (xhr) {
                $('#role_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#adminUpdate').attr('disabled', false); // enable the submit button
                $('#adminUpdate').html('Update'); // remove spinner from button
            }
        });
    });
});

// Delete Admin
$(document).on("click", ".admin-delete-btn", function (e) {
    e.preventDefault();
    var adminId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete slider
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-admin/' + adminId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.status) {
                        // Update content or remove the deleted admin from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Admin has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to delete admin. ' + response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error!', 'Failed to delete admin. Check console for details.', 'error');
                }
            });
        }
    });
});


// Set Permission
$(function () {
    $("#permissionsForm").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#submit_button').attr('disabled', true); // disable the submit button
                $('#submit_button').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Permission Setted Successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful role submission
                } else {
                    toastr.error('Failed to save permission . Please try again.');
                }
            },
            error: function (xhr) {
                $('#role_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#submit_button').attr('disabled', false); // enable the submit button
                $('#submit_button').html('Set Permission'); // remove spinner from button
            }
        });
    });
});

// Add Coming Soon
$(function () {
    $("#coming_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#coming_submit').attr('disabled', true); // disable the submit button
                $('#coming_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#coming_form input').on('change', function () {
                        $('#coming_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Uploded successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.href = '/dashboard/coming-soon';
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else {
                    toastr.error('Failed to save Coming Soon. Please try again.');
                }
            },
            error: function (xhr) {
                $('#coming_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#coming_submit').attr('disabled', false); // enable the submit button
                $('#coming_submit').html('Save'); // remove spinner from button
            }
        });
    });
});

// Edit Coming Soon
$(function () {
    $("#edit_coming_form").on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function () {
                $(document).find('span.error-text').text('');
                $('#edit_coming_submit').attr('disabled', true); // disable the submit button
                $('#edit_coming_submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...'); // add spinner to button
            },
            success: function (data) {
                if (data.status == 0) {
                    // Clear the validation error messages if any input field is changed
                    $('#edit_coming_form input').on('change', function () {
                        $('#edit_coming_error').text('');
                    });
                    $.each(data.error, function (prefix, val) {
                        $('span.' + prefix + '_error').text(val[0]);
                    });

                } else if (data.status == 1) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Series updated successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            // Reset the form
                            window.location.reload();
                        }
                    });
                    // Perform any necessary actions after successful Series submission
                } else {
                    toastr.error('Failed to save Series. Please try again.');
                }
            },
            error: function (xhr) {
                $('#edit_coming_error').text('Something went wrong. Please try again.');
            },
            complete: function () {
                $('#edit_coming_submit').attr('disabled', false); // enable the submit button
                $('#edit_coming_submit').html('Update'); // remove spinner from button
            }
        });
    });
});

// Delete Coming Soon
$(document).on("click", ".coming-delete-btn", function (e) {
    e.preventDefault();
    var comingId = $(this).data("id");

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to delete coming
            $.ajax({
                type: 'GET',
                url: '/dashboard/delete-coming/' + comingId,
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        // Update content or remove the deleted movie from the page
                        // This depends on your HTML structure
                        $(e.target).closest('tr').remove();
                        Swal.fire('Deleted!', 'Successfully deleted.', 'success');
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete coming.', 'error');
                }
            });
        }
    });
});