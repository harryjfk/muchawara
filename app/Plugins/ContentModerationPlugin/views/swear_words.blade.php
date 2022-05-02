@extends('admin.layouts.admin')
@section('content')
@parent

<style type="text/css">
.section-first-col {
    min-height: 0px;
}
.delete-icon {
    font-size: 20px;
    cursor: pointer;
    color: red;
}
.add-creditpackage-col{
    width: 100%;
}
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{{trans('ContentModerationPlugin.swear_words_heading')}}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">

      <div class="col-md-12 section-first-col user-section-first-col">

        <div class="row">

            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{trans('ContentModerationPlugin.add_new_word_title')}}}</p>
                <div class="form-group">
                    <label class="package-label">{{{trans('ContentModerationPlugin.add_new_word_input_title')}}}</label>
                    <input type="text" value = "" placeholder="{{{trans('ContentModerationPlugin.add_new_word_input_placeholder')}}}" id = "new-word"  class="form-control  input-border-custom">
                </div>
                <div class="form-group">
                    <label class="package-label">{{{trans('ContentModerationPlugin.add_new_word_choose_pattern_input_title')}}}</label>
                    <select name="default-lang" class="form-control input-border-custom select-custom" id="new-word-match-all-pattern">
                        <option value = "false">{{{trans('ContentModerationPlugin.match_only_word')}}}</option>
                        <option value = "true">{{{trans('ContentModerationPlugin.match_all_patterns')}}}</option>
                    </select>
                </div>
                <button type="button" id = "add-word-btn" class="btn btn-info btn-addpackage btn-custom btn-set">{{{trans('ContentModerationPlugin.add_btn')}}}</button>
            </div>

            <div class="col-md-12 user-dropdown-col">
               
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="users-text">{{{trans('ContentModerationPlugin.swear_word_list')}}}</p>
                  </div>
                  <table class="table" id="user-table">
                     <thead>
                        <tr style="background-color: #323b42;">
                           <!-- <th>ID</th> -->
                           <th>{{{trans('ContentModerationPlugin.word')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.match_all_patterns')}}}</th>
                           <th>{{{trans('ContentModerationPlugin.delete')}}}</th>
                        </tr>
                     </thead>
                     <tbody>


                        @if(count($swear_words) > 0) 

                            @foreach($swear_words as $swear_word) 
                                
                               
                                    <tr id = "row-{{{$swear_word->id}}}">
                                        <td>{{{$swear_word->word}}}</td>
                                        <td>
                                                
                                            <label class="switch">
                                                <input class="switch-input match-all-switch" id = "match-all-switch-{{{$swear_word->id}}}" type="checkbox" data-word-id="{{{$swear_word->id}}}"
                                                @if($swear_word->match_all_pattern) checked @endif >
                                                <span class="switch-label"></span> 
                                                <span class="switch-handle"></span>
                                            </label>

                                        </td>
                                        <td><i class="fa fa-trash-o delete-icon" data-word-id="{{{$swear_word->id}}}"></i></td>
                                    </tr>
                                

                            @endforeach

                        @else
                        @endif
                       



                     </tbody>
                  </table>
               </div>
               
               
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
        datatable = $('#user-table').DataTable({
          paging: false
        });


        $("#add-word-btn").on('click',function(){
            var word = $("#new-word").val();
            var match_all_pattern = $("#new-word-match-all-pattern").val();
            if (word == '') {
                toastr.warning('{{{trans('ContentModerationPlugin.new_word_required')}}}');
                return;
            }
                
            $.post("{{{url('admin/plugins/swear-words/add')}}}",
                {_token:"{{{csrf_token()}}}", word: word, match_all_pattern:match_all_pattern},
                function(res) {
                    
                    if (res.status == 'success') {
                      toastr.success(res.message);

                        var checked = '';
                        if (res.word_object.match_all_pattern == 1) {
                            checked = 'checked';
                        } 

                        $("#user-table > tbody").prepend('<tr id = "row-'+res.word_object.id+'"><td>'+res.word_object.word+'</td><td><label class="switch"><input class="switch-input match-all-switch" id = "match-all-switch-'+res.word_object.id+'" type="checkbox" data-word-id="'+res.word_object.id+'" '+checked+' ><span class="switch-label"></span> <span class="switch-handle"></span></label></td><td><i class="fa fa-trash-o delete-icon" data-word-id="'+res.word_object.id+'"></i></td></tr>'
                        );

        //                 $('#user-table').dataTable().fnDestroy();
        //                 $('#user-table').dataTable({
        //   paging: false
        // });

                        return ;
                    } 
                    toastr.error(res.message);  
                }
            );



        });



        $("#user-table").on('click','.match-all-switch',function(){

            var elem = $(this);
            var word_id = $(this).data('word-id');

            var match_all_pattern = 'false';
            if($(this).is(":checked")) {
                match_all_pattern = 'true';
            }

            $.post("{{{url('admin/plugins/swear-words/set-match-all-pattern')}}}",
                {_token:"{{{csrf_token()}}}", word_id: word_id, match_all_pattern, match_all_pattern},
                function(res) {
                    
                    if (res.status == 'success') {
                       if (res.match_all_pattern==1)
                            elem.prop('checked', true);
                        else
                            elem.prop('checked', false);
                        return true;
                    } 
                    // elem.prop('checked', false);
                    toastr.error(res.message);  
                }
            );

        });





        $("#user-table").on('click','.delete-icon',function(){

            var word_id = $(this).data('word-id');
            
            $.post("{{{url('admin/plugins/swear-words/delete')}}}",
                {_token:"{{{csrf_token()}}}", word_id: word_id},
                function(res) {
                    
                    if (res.status == 'success') {

                        $("#row-"+word_id).remove();

                        toastr.success(res.message);


                    } else {
                        toastr.error(res.message);
                    }   
                }
            );


        });




    });
</script>
@endsection