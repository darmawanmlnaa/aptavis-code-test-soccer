@extends('layouts.base')
@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClubModal">
    <i class="bi bi-plus-circle-fill"></i>
    </button>

    <div class="card mt-3">
        <h5 class="card-header text-center">Club</h5>
        <div class="card-body">
            <table id="club-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal fade" id="addClubModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Club</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('club.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="city" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="modalEdit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEdit">Edit Club</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                <input type="hidden" name="_method" value="put"/>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="edit-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="edit-city" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- DataTable --}}
    <script type="text/javascript">
        $(document).ready(function () {
            $('#club-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('club') }}",
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'city', name: 'city' },
                    { data: 'action', name: 'action' },
                ]
            });
        });
    </script>
    
    {{-- Update --}}
    <script type="text/javascript">
        function openModal(id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: 'api/club/'+id,
                success: function (response) {
                   const data = response.data;
                   $('#edit-name').val(data.name);
                   $('#edit-city').val(data.city);
                   $('#editForm').attr('action', response.path+'/update/'+data.id)
                   $('#edit').modal('show');
                },
                error: function (response) {
                    Swal.fire({
                        title: 'Error',
                        text: response.error_message,
                        icon: 'warning',
                    })
                }
            });
        }
    </script>

    {{-- Delete Confirmation --}}
    <script type="text/javascript">
        $(document).ready(function () {
            // Delete Club confirmation modal
            $('#club-table').on('click', '.delete-club', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Delete Club',
                    text: 'Are you sure you want to delete this club?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        var form = $('<form>', {
                            'action': url,
                            'method': 'POST',
                            'style': 'display:none;'
                        }).append('@csrf', '@method('DELETE')');
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush