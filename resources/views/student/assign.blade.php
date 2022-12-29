<form action="{{ route('teacherAssign') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Assign Teacher</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="teacher">Teacher</label>
                <input type="hidden" name="student_id" value="{{ $student_id }}">
                {{ Form::select('assigned_to',['' => 'Please Select'] + $teacherList,null,['id' => 'exp','class'=> 'form-control']) }}
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
</form>
