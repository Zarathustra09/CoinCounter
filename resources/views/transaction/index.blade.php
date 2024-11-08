@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transaction Table</h4>
                    <div class="table-responsive">
                        <table id="transaction-table" class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Machine</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Purchased At</th>
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
            $('#transaction-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/transactions',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'machine.identifier', name: 'machine.identifier', title: 'Machine' },
                    { data: 'item.name', name: 'item.name', title: 'Item' },
                    { data: 'quantity', name: 'quantity' },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        render: function(data, type, row) {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'purchased_at', name: 'purchased_at' }
                ]
            });
        });
    </script>
@endpush

