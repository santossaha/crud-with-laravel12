<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Upload Content</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


</head>
<body>
    <div class="container-fluid py-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div>
            @if ($errors->has('xml_file'))
                <div class="alert alert-danger">
                    {{ $errors->first('xml_file') }}
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <!-- XML Import Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">

                                    <div class="card-body">
                                        <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="xml_file" class="form-label">Select XML File</label>
                                                <input type="file" class="form-control" id="xml_file" name="xml_file" accept=".xml" required>

                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload"></i> Import XML
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Contacts Table -->
                        <div class="table-responsive">
                            <div class="card-body d-flex justify-content-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addContactModal">
                                    <i class="fas fa-plus"></i> Add Contact
                                </button>
                            </div>

                            <table id="contactsTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Contact Modal -->
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContactModalLabel">Add New Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addContactForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="editContactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContactModalLabel">Edit Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editContactForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_contact_id" name="contact_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#contactsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("contacts.list") }}',
                    type: 'GET'
                },
                columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'phone' },
            { data: 'action', orderable: false, searchable: false }
        ]
            });

            // Add Contact Form
            $('#addContactForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("contacts.store") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.ajax.reload();
                            $('#addContactModal').modal('hide');
                            $('#addContactForm')[0].reset();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = 'Something is worng!!';
                        toastr.error(message);
                    }
                });
            });

            // Edit Contact
            $(document).on('click', '.edit-contact', function() {
                var contactId = $(this).data('id');
                $.ajax({
                    url: '/contacts/' + contactId,
                    type: 'GET',
                    success: function(response) {

                        if (response.success) {
                           
                            $('#edit_contact_id').val(response.data.id);
                            $('#edit_name').val(response.data.name);
                            $('#edit_phone').val(response.data.phone);
                            $('#editContactModal').modal('show');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something is worng!!');
                    }
                });
            });

            // Update Contact Form
            $('#editContactForm').submit(function(e) {
                e.preventDefault();
                var contactId = $('#edit_contact_id').val();
                $.ajax({
                    url: '/contacts/' + contactId,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.ajax.reload();
                            $('#editContactModal').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something is worng!!');
                    }
                });
            });

            // Delete Contact (No confirmation)
            $(document).on('click', '.delete-contact', function() {
                var contactId = $(this).data('id');
                $.ajax({
                    url: '/contacts/' + contactId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Contact deleted successfully!');
                            table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something is worng!!');
                    }
                });
            });
        });
    </script>
</body>
</html>
