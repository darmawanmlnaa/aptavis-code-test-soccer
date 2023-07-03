@extends('layouts.base')
@section('content')
    <div class="card">
        <h5 class="card-header text-center">Add New Match Records</h5>
        <div class="card-body">
            <form action ="{{route('score.store')}}" method="post">
                @csrf
                <div class="input_fields_wrap">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="first_club_id" class="form-label">Club 1</label>
                                <select class="form-select" aria-label="Default select example" name="first_club_id[1]" id="first_club_id" required>
                                    <option value="" selected>Select Club</option>
                                    @foreach ($clubs as $club)
                                        <option value="{{$club->id}}">{{$club->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="second_club_id" class="form-label">Club 2</label>
                                <select class="form-select" aria-label="Default select example" name="second_club_id[1]" id="second_club_id" required>
                                    <option value="" selected>Select Club</option>
                                    @foreach ($clubs as $club)
                                        <option value="{{$club->id}}">{{$club->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="score_first_club" class="form-label">Score Club 1</label>
                                <input type="text" class="form-control" id="score_first_club" name="score_first_club[1]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="score_second_club" class="form-label">Score Club 2</label>
                                <input type="text" class="form-control" id="score_second_club" name="score_second_club[1]" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary add_field_button">Add More Fields</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card mt-5 mb-5">
        <h5 class="card-header text-center">Scoreboard</h5>
        <div class="card-body">
            <table id="score-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Match</th>
                        <th>Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="updateScoreModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Scoreboard</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post" id="editScore">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="first_club_id" class="form-label">Club 1</label>
                    <select class="form-select" aria-label="Default select example" name="first_club_id" id="edit-first-club" required>
                        <option value="" selected>Select Club</option>
                        @foreach ($clubs as $club)
                            <option value="{{$club->id}}">{{$club->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="score_first_club" class="form-label">Score Club 1</label>
                    <input type="text" name="score_first_club" class="form-control" id="first-score" required>
                </div>
                <div class="mb-3">
                    <label for="second_club_id" class="form-label">Club 2</label>
                    <select class="form-select" aria-label="Default select example" name="second_club_id" id="edit-second-club" required>
                        <option value="" selected>Select Club</option>
                        @foreach ($clubs as $club)
                            <option value="{{$club->id}}">{{$club->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="score_second_club" class="form-label">Score Club 2</label>
                    <input type="text" name="score_second_club" class="form-control" id="second-score" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning text-white">Save changes</button>
            </div>
        </form>
        </div>
    </div>
    </div>

@endsection

@push('script')
    {{-- Dynamic Form --}}
    <script type="text/javascript">
        $(document).ready(function () {
            // var max_fields = 10; // maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); // Fields wrapper
            var add_button = $(".add_field_button"); // Add button ID

            var x = 2; // initial text box count
            $(add_button).click(function (e) { // on add input button click
                e.preventDefault();
                // if (x <= max_fields) { // max input box allowed
                    x++; // text box increment
                    var clubNumber = x + (x - 3);
                    var scoreNumber = x + (x - 3);
                    var selectOptions = "";
                    @foreach ($clubs as $club)
                        selectOptions += `<option value="{{ $club->id }}">{{ $club->name }}</option>`;
                    @endforeach
                    $(wrapper).append(`
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="first_club_id" class="form-label">Club ${clubNumber}</label>
                                    <select class="form-select" aria-label="Default select example" name="first_club_id[${x}]" required>
                                        <option selected>Select Club</option>
                                        ${selectOptions}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="second_club_id" class="form-label">Club ${clubNumber + 1}</label>
                                    <select class="form-select" aria-label="Default select example" name="second_club_id[${x + 1}]" required>
                                        <option selected>Select Club</option>
                                        ${selectOptions}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="score_first_club" class="form-label">Score Club ${scoreNumber}</label>
                                    <input type="text" class="form-control" id="score_first_club" name="score_first_club[${x}]" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="score_second_club" class="form-label">Score Club ${scoreNumber + 1}</label>
                                    <input type="text" class="form-control" id="score_second_club" name="score_second_club[${x + 1}]" required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button class="btn btn-danger remove_field">Remove</button>
                            </div>
                        </div>
                    `); // add input boxes.
                // }
            });

            $(wrapper).on("click", ".remove_field", function (e) { // user click on remove text
                e.preventDefault();
                $(this).closest('.row').remove();
                x--;
            });
        });
    </script>

    {{-- Searchable Select --}}
    {{-- <script type="text/javascript">
        $(document).ready(function() {
            $("#first_club_id").selectize({
                plugins: ["auto_select_on_type"],
            });
        });
    </script> --}}

    {{-- DataTable --}}
    <script type="text/javascript">
        $(document).ready(function () {
            $('#score-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('score') }}",
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    { data: 'match', name: 'match' },
                    { data: 'score', name: 'score' },
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
                url: '/score/show/'+id,
                success: function (response) {
                   const data = response.data;
                   $('#edit-first-club').val(data.first_club_id);
                   $('#first-score').val(data.score_first_club);
                   $('#edit-second-club').val(data.second_club_id);
                   $('#second-score').val(data.score_second_club);
                   $('#editScore').attr('action', response.path+'/update/'+data.id)
                   $('#editScore').append('<input type="hidden" name="_method" value="PUT">');
                   $('#updateScoreModal').modal('show');
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
            // Delete Score confirmation modal
            $('#score-table').on('click', '.delete-score', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Delete Scoreboard',
                    text: 'Are you sure you want to delete this record?',
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