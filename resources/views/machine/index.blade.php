@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Machines Table</h4>
                    <button class="btn btn-sm btn-success mb-3" onclick="createMachine()">
                        <i class="mdi mdi-plus"></i> Create Machine
                    </button>
                    <div class="table-responsive">
                        <table id="machines-table" class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Location</th>
                                <th>Identifier</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Data will be populated by DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#machines-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/machines',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'location', name: 'location' },
                    { data: 'identifier', name: 'identifier' },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary" onclick="editMachine(${row.id})">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteMachine(${row.id})">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });
        });

        function createMachine() {
            Swal.fire({
                title: 'Create Machine',
                html: `
                    <input type="text" id="machine-location" class="swal2-input" placeholder="Location">
                    <input type="text" id="machine-identifier" class="swal2-input" placeholder="Identifier">
                `,
                showCancelButton: true,
                confirmButtonText: 'Create',
                preConfirm: () => {
                    const location = $('#machine-location').val();
                    const identifier = $('#machine-identifier').val();
                    return { location, identifier };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { location, identifier } = result.value;
                    $.ajax({
                        url: '/api/machines',
                        type: 'POST',
                        data: {
                            location: location,
                            identifier: identifier,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Created!', 'Machine has been created.', 'success');
                            $('#machines-table').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }

        function editMachine(id) {
            $.get(`/api/machines/${id}`, function(machine) {
                Swal.fire({
                    title: 'Edit Machine',
                    html: `
                        <input type="hidden" id="machine-id" value="${machine.id}">
                        <input type="text" id="machine-location" class="swal2-input" placeholder="Location" value="${machine.location}">
                        <input type="text" id="machine-identifier" class="swal2-input" placeholder="Identifier" value="${machine.identifier}">
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    preConfirm: () => {
                        const id = $('#machine-id').val();
                        const location = $('#machine-location').val();
                        const identifier = $('#machine-identifier').val();
                        return { id, location, identifier };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { id, location, identifier } = result.value;
                        $.ajax({
                            url: `/api/machines/${id}`,
                            type: 'PUT',
                            data: {
                                location: location,
                                identifier: identifier,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Updated!', 'Machine has been updated.', 'success');
                                $('#machines-table').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });
        }

        function deleteMachine(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/machines/${id}`,
                        type: 'DELETE',
                        success: function(result) {
                            Swal.fire(
                                'Deleted!',
                                'Machine has been deleted.',
                                'success'
                            );
                            $('#machines-table').DataTable().ajax.reload();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Your machine is safe :)',
                        'error'
                    );
                }
            });
        }
    </script>
@endpush
