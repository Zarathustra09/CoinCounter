@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Inventory Table</h4>
                    <button class="btn btn-sm btn-success mb-3" onclick="createInventory()">
                        <i class="mdi mdi-plus"></i> Create Inventory
                    </button>
                    <div class="table-responsive">
                        <table id="inventory-table" class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Machine ID</th>
                                <th>Item ID</th>
                                <th>Quantity</th>
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
            $('#inventory-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/inventories',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'machine.identifier', name: 'machine.identifier', title: 'Machine' },
                    { data: 'item.name', name: 'item.name', title: 'Item' },
                    { data: 'quantity', name: 'quantity' },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-sm btn-primary" onclick="editInventory(${row.id})">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteInventory(${row.id})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    `;
                        }
                    }
                ]
            });
        });

        function createInventory() {
            $.when(
                $.get('/api/machines'),
                $.get('/api/items'),
                $.get('/api/inventories')
            ).done(function(machinesResponse, itemsResponse, inventoriesResponse) {
                const machines = machinesResponse[0].data; // Access the data property
                const items = itemsResponse[0]; // Assuming items response is an array
                const inventories = inventoriesResponse[0]; // Assuming inventories response is an array

                // Get the IDs of items already in the machine
                const itemIdsInMachine = inventories.map(inventory => inventory.item_id);

                // Filter out items already in the machine
                const availableItems = items.filter(item => !itemIdsInMachine.includes(item.id));

                let machineOptions = machines.map(machine => `<option value="${machine.id}">${machine.identifier}</option>`).join('');
                let itemOptions = availableItems.map(item => `<option value="${item.id}">${item.name}</option>`).join('');

                Swal.fire({
                    title: 'Create Inventory',
                    html: `
                <select id="inventory-machine-id" class="swal2-input">
                    <option value="" disabled selected>Select Machine</option>
                    ${machineOptions}
                </select>
                <select id="inventory-item-id" class="swal2-input">
                    <option value="" disabled selected>Select Item</option>
                    ${itemOptions}
                </select>
                <input type="number" id="inventory-quantity" class="swal2-input" placeholder="Quantity">
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Create',
                    preConfirm: () => {
                        const machine_id = $('#inventory-machine-id').val();
                        const item_id = $('#inventory-item-id').val();
                        const quantity = $('#inventory-quantity').val();
                        return { machine_id, item_id, quantity };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { machine_id, item_id, quantity } = result.value;
                        $.ajax({
                            url: '/api/inventories',
                            type: 'POST',
                            data: {
                                machine_id: machine_id,
                                item_id: item_id,
                                quantity: quantity,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Created!', 'Inventory has been created.', 'success');
                                $('#inventory-table').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });
        }

        function editInventory(id) {
            $.when(
                $.get(`/api/inventories/${id}`),
                $.get('/api/machines'),
                $.get('/api/items'),
                $.get('/api/inventories')
            ).done(function(inventoryResponse, machinesResponse, itemsResponse, inventoriesResponse) {
                const inventory = inventoryResponse[0];
                const machines = machinesResponse[0].data;
                const items = itemsResponse[0];
                const inventories = inventoriesResponse[0];

                // Get the IDs of items already in the machine
                const itemIdsInMachine = inventories.map(inventory => inventory.item_id);

                // Filter out items already in the machine, but include the current item
                const availableItems = items.filter(item => !itemIdsInMachine.includes(item.id) || item.id === inventory.item_id);

                let machineOptions = machines.map(machine => `<option value="${machine.id}" ${machine.id === inventory.machine_id ? 'selected' : ''}>${machine.identifier}</option>`).join('');
                let itemOptions = availableItems.map(item => `<option value="${item.id}" ${item.id === inventory.item_id ? 'selected' : ''}>${item.name}</option>`).join('');

                Swal.fire({
                    title: 'Edit Inventory',
                    html: `
                <input type="hidden" id="inventory-id" value="${inventory.id}">
                <select id="inventory-machine-id" class="swal2-input">
                    <option value="" disabled>Select Machine</option>
                    ${machineOptions}
                </select>
                <select id="inventory-item-id" class="swal2-input">
                    <option value="" disabled>Select Item</option>
                    ${itemOptions}
                </select>
                <input type="number" id="inventory-quantity" class="swal2-input" placeholder="Quantity" value="${inventory.quantity}">
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    preConfirm: () => {
                        const id = $('#inventory-id').val();
                        const machine_id = $('#inventory-machine-id').val();
                        const item_id = $('#inventory-item-id').val();
                        const quantity = $('#inventory-quantity').val();
                        return { id, machine_id, item_id, quantity };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { id, machine_id, item_id, quantity } = result.value;
                        $.ajax({
                            url: `/api/inventories/${id}`,
                            type: 'PUT',
                            data: {
                                machine_id: machine_id,
                                item_id: item_id,
                                quantity: quantity,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Updated!', 'Inventory has been updated.', 'success');
                                $('#inventory-table').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });
        }

        function deleteInventory(id) {
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
                        url: `/api/inventories/${id}`,
                        type: 'DELETE',
                        success: function(result) {
                            Swal.fire(
                                'Deleted!',
                                'Inventory has been deleted.',
                                'success'
                            );
                            $('#inventory-table').DataTable().ajax.reload();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Your inventory is safe :)',
                        'error'
                    );
                }
            });
        }
    </script>
@endpush
