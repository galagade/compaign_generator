@extends("la.layouts.app")

@section("contentheader_title", "Campaign")
@section("contentheader_description", "Campaign Questions")
@section("section", "Campaign")
@section("sub_section", "Questions")
@section("htmlheader_title", "Campaign Questions")

@section("headerElems")  
@la_access("Campaign", "create")
	
	
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Question</button>
@endla_access
@endsection

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="box box-success">
	<div class="box-header">
	     <h5>{{$campaign->name }}   Campaign</h5>
	     <a href="{{url(config('laraadmin.adminRoute') . '/campaign')}}" class="btn btn-success btn-sm" >Go Back</a> 
		<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#FormModal">View Form</button>
		<a href="{{URL::route('node.campaign.questions.answers', ['id'=>$campaign->id, 'page'=>1])}}" class="btn btn-success btn-sm" >View Answers</a>
		<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#HTMLModal">View Form html code</button>
	</div>
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

@la_access("Campaign", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Question</h4>
			</div>
			{!! Form::open(['route' => 'node.campaign.store.questions', 'id' => 'organization-add-form']) !!}
			{!! Form::hidden('campaign_id', $campaign->id) !!}
			{!! Form::hidden('total', 1) !!} 
			<div class="modal-body">
				<div class="box-body">
                    <div class="form-group {!! \ViewHelper::showHasError('name') !!}">
			         {!! Form::Label('name', 'Question Name *', ['class' => '  ']) !!}
			         <input type="text" name="name"  value="{!! old('name') !!}" class='form-control' placeholder='Question Name e.g Firstname'>
			         {!! \ViewHelper::showErrors('name') !!} 
			    </div>
			    <div class="form-group {!! \ViewHelper::showHasError('type') !!}">
			         {!! Form::Label('name', 'Question type *', ['class' => '  ']) !!}
				        	<select class="form-control Questiontype" name="type">
				        	<option value="">Select Question Type</option>
				        		@foreach($question_types as $type)
				        		<option value="{{ $type->name}}" {!! ( old('type') == $type->name ? 'selected' : '') !!} >{{ $type->name}}</option>
				        		@endforeach
				        	</select> 
			         
			         {!! \ViewHelper::showErrors('type') !!}
			     </div>
			    <div class="form-group {!! \ViewHelper::showHasError('values') !!} typevalue_0" id="typevalue_0" {!! \ViewHelper::handleType('type') !!}>
			         {!! Form::Label('name', 'Question type values *', ['class' => '  ']) !!}
				     {!! Form::text('values', 
			                old('values'),
			                ['class' => 'form-control', 'placeholder' => 'Separate values with a comma'] ) !!}
			        {!! \ViewHelper::showErrors('values') !!}  
			   </div>
			        
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

@la_access("Campaign", "create")
<div class="modal fade" id="EditModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Question</h4>
			</div>
			{!! Form::open(['route' => 'node.campaign.question.edit', 'id' => 'campaign-edit-form']) !!}
			{!! Form::hidden('campaign_id', $campaign->id) !!}
			 <input type="hidden" name="id"  id="id" value="{!! old('id') !!}">
			 
			<div class="modal-body">
				<div class="box-body">
                    <div class="form-group {!! \ViewHelper::showHasError('name') !!}">
			         {!! Form::Label('name', 'Question Name *', ['class' => '  ']) !!}
			         <input type="text" name="name"  value="{!! old('name') !!}" id="name" class='form-control' placeholder='Question Name e.g Firstname'>
			         {!! \ViewHelper::showErrors('name') !!} 
			    </div>
			    <div class="form-group {!! \ViewHelper::showHasError('type') !!}">
			         {!! Form::Label('name', 'Question type *', ['class' => '  ']) !!}
				        	<select class="form-control Questiontype" name="type" id="type">
				        	<option value="">Select Question Type</option>
				        		@foreach($question_types as $type)
				        		<option value="{{ $type->name}}" {!! ( old('type') == $type->name ? 'selected' : '') !!} >{{ $type->name}}</option>
				        		@endforeach
				        	</select> 
			         
			         {!! \ViewHelper::showErrors('type') !!}
			     </div>
			    <div class="form-group {!! \ViewHelper::showHasError('values') !!} typevalue_0" {!! \ViewHelper::handleType('type',0,true) !!}>
			         {!! Form::Label('name', 'Question type values *', ['class' => '  ']) !!}
				     {!! Form::text('values', 
			                old('values'),
			                ['class' => 'form-control', 'id' => 'values', 'placeholder' => 'Separate values with a comma'] ) !!}
			        {!! \ViewHelper::showErrors('values') !!}  
			   </div>
			        
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

<div class="modal fade" id="FormModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">{{ $campaign->name }} Form</h4>
			</div>
			{!! Form::open(['route' => 'node.form.post', 'id' => 'campaign-sample-form']) !!}
			{!! Form::hidden('campaign_id', $campaign->id) !!}
			 <input type="hidden" name="redirect_url"  value="sample">
			 
			<div class="modal-body">
				{!! \ViewHelper::CampaignForm($campaign) !!}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
<div class="modal fade" id="HTMLModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">{{ $campaign->name }} Form</h4>
			</div>
			
			 
			<div class="modal-body">
			
				{!! htmlentities(\ViewHelper::CampaignFormHtml($campaign)) !!}
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				
			</div>
			
		</div>
	</div><?php  //htmlentities(string) ?>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') .'/questions/'.$campaign->id. '/campaign_q_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#user-add-form").validate({
		
	});
	var type_array =['Checkbox', 'Dropdown', 'Multiselect', 'Radio']
	$('.Questiontype').change(function(){
		console.log('changed')
		var string = '.typevalue_0';
		console.log($.inArray($(this).val(), type_array))
		if($.inArray($(this).val(), type_array) != -1){
			$(string).show()
		}else{
			$(string).hide()
		}
		
	})

	$(document).on('click', '.edit_btn', function(){
		console.log($(this).attr('data-meta'))
		var data = JSON.parse($(this).attr('data-meta'))
		$('#campaign-edit-form').find('#name').val(data[1])
		$('#campaign-edit-form').find('#id').val(data[0])
		$('#campaign-edit-form').find('#type').val(data[3])
		$('#campaign-edit-form').find('#values').val(data[2])
		console.log(data[2]);
		var string = '.typevalue_0';

		if($.inArray(data[3], ['Checkbox', 'Dropdown', 'Multiselect', 'Radio']) != -1){
			$('#campaign-edit-form').find(string).show()
		}else{
			$('#campaign-edit-form').find(string).hide()
		}
	})
});
</script>
<?php   ?>
@if (Session()->has('error_edit'))
	<script>
		$('#EditModal').modal('show')
	</script>
@endif

@if (Session()->has('error_add'))
	<script>
		$('#AddModal').modal('show')
	</script>
@endif
@endpush