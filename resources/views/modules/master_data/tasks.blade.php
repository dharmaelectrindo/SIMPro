




    <!-- Page Header -->
   
                
            
    <!-- Page Header Close -->

    <!-- Start::row -->
    <div class="row">
      <div class="col-xl-8 mt-3">
          <div class="card custom-card">
              <div class="card-body" style="min-height: 300px;max-height:300px;overflow:auto;">
  
                  TEMPLATE PROJECT -
                  <strong>{{$templateName ?? ''}}</strong>
                  <div id="treeview2">
                      <ul id="treeview" class="filetree">
                          @foreach($task as $induk) @php if($induk->children->count() > 0){ $class1 =
                          "folder"; } else{ $class1 = "file"; } @endphp
                          <li>
                              <span class="{{$class1 ?? ''}}"  data-id="{{$induk->id}}">&nbsp;&nbsp;
                                  {{ $induk->description }}</span>
                              @if($induk->children->count() > 0)
                              <ul>
                                  @foreach($induk->children as $anak) @php if($anak->children->count() > 0){
                                  $class2 = "folder"; } else{ $class2 = "file"; } @endphp
                                  <li >
                                      <span class="{{$class2 ?? ''}}" data-id="{{$anak->id}}" data-parent="{{$induk->description}}">&nbsp;&nbsp;
                                          {{ $anak->description }} </span>
                                      @if($anak->children->count() > 0)
                                      <ul>
                                          @foreach($anak->children as $cucu) @php if($cucu->children->count() > 0){
                                          $class3 = "folder"; } else{ $class3 = "file"; } @endphp
                                          <li>
                                              <span class="{{$class3 ?? ''}}" data-id="{{$cucu->id}}">&nbsp;&nbsp;
                                                  {{ $cucu->description }}</span>
                                              @if($cucu->children->count() > 0)
                                              <ul>
                                                  @foreach($cucu->children as $cicit) @php if($cucu->children->count() > 0){
                                                  $class4 = "folder"; } else{ $class4 = "file"; } @endphp
                                                  <li >
                                                      <span class="{{$class4 ?? ''}}" data-id="{{$cicit->id}}">&nbsp;&nbsp;
                                                          {{ $cicit->description }}</span>
  
                                                  </li>
  
                                                  @endforeach
                                              </ul>
  
                                              @endif
                                          </li>
  
                                          @endforeach
                                      </ul>
  
                                      @endif
                                  </li>
                                  @endforeach
                              </ul>
                              @endif
                          </li>
                          @endforeach
                      </ul>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-xl-4 mt-3">
          <form id="tasksForm" name="tasksForm" class="form-horizontal">
              @csrf
              <div class="alert alert-danger error-msg-task" style="display:none">
                  <ul></ul>
              </div>
              
              <div class="row gy-2">
                  <div class="alert alert-danger error-msgTask" style="display:none">
                      <ul></ul>
                  </div>
                  <div class="col-xl-12">
                      <label for="parent" class="form-label">Parent</label>
                      <input type="text" id="parentName" name="parentName" class="form-control" placeholder="Parent Name.." disabled>
                  </div>  
                  <div class="col-xl-12">
                      <input type="hidden" name="taskID" id="taskID">
                      <input type="hidden" name="parentID" id="parentID">
                      <input type="hidden" name="templateForeign" id="templateForeign" value="{{$templateID ?? ''}}">
                      <label for="descriptions" class="form-label">Description</label>
                      <input type="text" id="descriptionTask" name="descriptionTask" class="form-control" placeholder="Description">
                  </div>     
                  <div class="col-xl-12">
                      <label for="descriptions" class="form-label">Sequence of Processes</label>
                      <input type="text" id="sortData" name="sortData" class="form-control" placeholder="Sequence">
                  </div>  
                  <div class="col-xl-12">
                      <button type="button" class="btn btn-primary" id="createTask" value="Create">Create</button>
                      <button type="button" class="btn btn-warning" id="editTask" value="Edit">Edit</button>
                      <button type="button" class="btn btn-danger" id="deleteTask" value="Delete">Delete</button>
                      <button type="submit" class="btn btn-success" id="saveBtnTask" value="create">Save</button>
                  </div>
              </div>
              
      
          
     
  </form>
      </div>
  
  </div>
   <!--End::row -->
  
  
  <script>
  $(document).ready(function(){
  $("#saveBtnTask").fadeOut("fast");
  document.getElementById("saveBtnTask").disabled = true;
  document.getElementById("descriptionTask").disabled = true;
  document.getElementById("sortData").disabled = true;
  $("#treeview").treeview({
    animated: "fast",
    collapsed: true,
    unique: true,
  });
  
  
  // fourth example
  $("#black, #gray").treeview({
    control: "#treecontrol",
    persist: "cookie",
    cookieId: "treeview-black"
  });
  
  $('#createTask').click(function () {
          var id           = $("#taskID").val();
          var description  = $("#descriptionTask").val();
          var sortData     = $("#sortData").val();
          $("#parentName").val(description);
          $('#saveBtnTask').val("create"); 
          $("#saveBtnTask").fadeIn("fast");
          $("#createTask").fadeOut("fast");
          $("#editTask").fadeOut("fast");
          $("#deleteTask").fadeOut("fast");;
          document.getElementById("saveBtnTask").disabled = false;
          document.getElementById("descriptionTask").disabled = false;
          document.getElementById("sortData").disabled = false;
          $("#descriptionTask").val("");
          $("#sortData").val(sortData+".");
          $("#parentID").val("");
          $("#taskID").val("");
          document.getElementById("descriptionTask").focus();
          $("#parentID").val(id);
      });
  
      $('#editTask').click(function () {
          var id           = $("#taskID").val();
          var description  = $("#descriptionTask").val();
          $('#saveBtnTask').val("create"); 
          $("#saveBtnTask").fadeIn("fast");
          $("#createTask").fadeOut("fast");
          $("#editTask").fadeOut("fast");
          $("#deleteTask").fadeOut("fast");;
          document.getElementById("saveBtnTask").disabled = false;
          document.getElementById("descriptionTask").disabled = false;
          document.getElementById("sortData").disabled = false;
          document.getElementById("descriptionTask").focus();
      });
      $('#deleteTask').click(function () {
          var id           = $("#taskID").val();
          Swal.fire({
                  title: 'Delete',
                  text: 'Are You Sure Delete This Data?',
                  showCancelButton: true,
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'No',
                  icon: 'question',
              }).then((result) => {
  
              if (result.isConfirmed) {
                  $.ajax({
                  type: "DELETE",
                  url: "/tasks/" + id,
                  data: {
                          _token: $('meta[name="csrf-token"]').attr('content'),
                          id:id  
                      },
                      success: function (data) {
                          Swal.fire('Delete Success', '', 'success');
                          var id  = $("#templateForeign").val();
                          $.post("{{ route('tasks.detail') }}",{id : id}, function (data) {
                              $('#modelHeadingDetail').html("Task Project");
                              $("#templateForeign").val(id);
                              $("#loadDetail").html(data);
                          });
                      },
                      error: function (data) {
                          console.log('Error:', data);
                          Swal.fire('Invalid Delete', '', 'error');
                          table.draw();
                      }
                  });
              }
  
              function printErrorMsg(msg) {
                  $('.error-msgTask').find('ul').html('');
                  $('.error-msgTask').css('display','block');
                  $.each( msg, function( key, value ) {
                      $(".error-msgTask").find("ul").append('<li>'+value+'</li>');
                  });
              }
  
          });
      });
      $("#treeview").on("click", ".folder", function(e) {
          var id = $(this).data('id');
          $.get("{{ route('tasks.index') }}" + '/' + id + '/edit', function (data) {
              $('#taskID').val(data.id);
              $('#sortData').val(data.sortData);
              $('#parentID').val(data.parentID);
              $('#descriptionTask').val(data.description);
              $("#createTask").fadeIn("fast");
              $("#editTask").fadeIn("fast");
              $("#deleteTask").fadeIn("fast");
          });
      
  });
  $("#treeview").on("click", ".file", function(e) {
      var id = $(this).data('id');
      var parentName = $(this).data('parent');
          $.get("{{ route('tasks.index') }}" + '/' + id + '/edit', function (data) {
              $('#taskID').val(data.id);
              $('#sortData').val(data.sortData);
              $('#parentID').val(data.parentID);
               $('#parentName').val(parentName);
              $('#descriptionTask').val(data.description);
              $("#createTask").fadeIn("fast");
              $("#editTask").fadeIn("fast");
              $("#deleteTask").fadeIn("fast");
              $("#saveBtnTask").fadeOut("fast");
              document.getElementById("saveBtnTask").disabled = true;
          });
  });
  $('#saveBtnTask').click(function (e) {
          e.preventDefault();
          $(this).html('<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Loading..');
  
          $.ajax({
              data: $('#tasksForm').serialize(),
              url: "{{ route('tasks.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                  if ($.isEmptyObject(data.error)) {
                          Swal.fire("Done!", data.message, "success");
                          var id  = $("#templateForeign").val();
                          
                          $.post("{{ route('tasks.detail') }}",{id : id}, function (data) {
                              $('#modelHeadingDetail').html("Task Project");
                              $("#templateForeign").val(id);
                              $("#loadDetail").html(data);
                          });
  
                      }else{
                          printErrorMsgTask(data.error);
                          $('#saveBtnTask').html('Save');
                      }
                          table.draw();
                      },
                          error: function (data) {
                              console.log('Error:', data);
                              $('#saveBtnTask').html('Save');
                      }
                  });
  
          function printErrorMsgTask(msg) {
              $('.error-msgTask').find('ul').html('');
              $('.error-msgTask').css('display','block');
              $.each( msg, function( key, value ) {
                  $(".error-msgTask").find("ul").append('<li>'+value+'</li>');
              });
          }
      });
  });
  
  </script>