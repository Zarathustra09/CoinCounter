@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Items Table</h4>
                    <button class="btn btn-sm btn-success mb-3" onclick="createItem()">
                        <i class="mdi mdi-plus"></i> Create Item
                    </button>
                    <div class="table-responsive">
                        <table id="items-table" class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Image</th>
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
            $('#items-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/items',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'category', name: 'category' },
                    { data: 'price', name: 'price' },
                    {
                        data: 'image_path',
                        name: 'image',
                        render: function(data) {
                            return data ? `<img src="{{ Storage::url('${data}') }}" alt="Item Image" width="50" height="50">` : 'No Image';
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary" onclick="editItem(${row.id})">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteItem(${row.id})">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });
        });

        function createItem() {
            Swal.fire({
                title: 'Create Item',
                html: `
                    <input type="text" id="item-name" class="swal2-input" placeholder="Name">
                    <input type="text" id="item-category" class="swal2-input" placeholder="Category">
                    <input type="number" id="item-price" class="swal2-input" placeholder="Price">
                    <input type="file" id="item-image" class="swal2-file">
                `,
                showCancelButton: true,
                confirmButtonText: 'Create',
                preConfirm: () => {
                    const name = $('#item-name').val();
                    const category = $('#item-category').val();
                    const price = $('#item-price').val();
                    const image = $('#item-image')[0].files[0];
                    return { name, category, price, image };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { name, category, price, image } = result.value;
                    const formData = new FormData();
                    formData.append('name', name);
                    formData.append('category', category);
                    formData.append('price', price);
                    formData.append('image_path', image);
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: '/api/items',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire('Created!', 'Item has been created.', 'success');
                            $('#items-table').DataTable().ajax.reload();
                        }
                    });
                }
            });
        }

        function editItem(id) {
            $.get(`/api/items/${id}`, function(item) {
                Swal.fire({
                    title: 'Edit Item',
                    html: `
                <input type="hidden" id="item-id" value="${item.id}">
                <input type="text" id="item-name" class="swal2-input" placeholder="Name" value="${item.name}">
                <input type="text" id="item-category" class="swal2-input" placeholder="Category" value="${item.category}">
                <input type="number" id="item-price" class="swal2-input" placeholder="Price" value="${item.price}">
                <input type="file" id="item-image" class="swal2-file">
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    preConfirm: () => {
                        const id = $('#item-id').val();
                        const name = $('#item-name').val();
                        const category = $('#item-category').val();
                        const price = $('#item-price').val();
                        const image = $('#item-image')[0].files[0];
                        return { id, name, category, price, image };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { id, name, category, price, image } = result.value;
                        const formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('name', name);
                        formData.append('category', category);
                        formData.append('price', price);
                        if (image) {
                            formData.append('image_path', image);
                        }
                        formData.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: `/api/items/${id}`,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire('Updated!', 'Item has been updated.', 'success');
                                $('#items-table').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });
        }

        function deleteItem(id) {
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
                        url: `/api/items/${id}`,
                        type: 'DELETE',
                        success: function(result) {
                            Swal.fire(
                                'Deleted!',
                                'Item has been deleted.',
                                'success'
                            );
                            $('#items-table').DataTable().ajax.reload();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Your item is safe :)',
                        'error'
                    );
                }
            });
        }
    </script>
@endpush
