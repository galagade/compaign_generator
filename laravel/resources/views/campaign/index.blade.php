@extends("la.layouts.app")

@section("contentheader_title", "Campaigns")
@section("contentheader_description", "Campaign listing")
@section("section", "Campaign")
@section("sub_section", "Listing")
@section("htmlheader_title", "Campaign Listing")

@section("headerElems")  
@la_access("Campaign", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Campaign</button>
@endla_access
@endsection

@section("main-content")



<div class="box box-success">
	<div class="box-header">
		<a class="btn btn-success btn-sm pull-right" href="{{URL::route('node.campaign.upload.index')}}">Upload Campaign</a>
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
				<h4 class="modal-title" id="myModalLabel">Add Campaign</h4>
			</div>
			{!! Form::open(['action' => 'campaign\CampaignController@store', 'id' => 'organization-add-form']) !!}
			

			<div class="modal-body">
				<div class="box-body">
                    <div class="form-group {!! \ViewHelper::showHasError('name') !!}">
			            {!! Form::Label('name', 'Campaign Name', ['class' => '  ']) !!}
			            {!! Form::text('name', 
			                old('name'),
			                ['class' => 'form-control', 'placeholder' => 'The Campaign Name'] ) !!}    
			            
			            {!! \ViewHelper::showErrors('name') !!}
			        </div>
			         <div class="form-group {!! \ViewHelper::showHasError('questions') !!}">
			            {!! Form::Label('questions', 'Number of Questions', ['class' => '  ']) !!}
			            {!! Form::Number('questions', 
			                old('questions'),
			                ['class' => 'form-control', 'min'=>1] ) !!}    
			            
			            {!! \ViewHelper::showErrors('questions') !!}
			        </div>
			         <div class="form-group {!! \ViewHelper::showHasError('description') !!}">
			            {!! Form::Label('description', 'Campaign Description', ['class' => '  ']) !!}
			            {!! Form::textarea('description', 
			                old('description'),
			                ['class' => 'form-control', 'placeholder' => 'Campaign Description'] ) !!}    
			            
			            {!! \ViewHelper::showErrors('description') !!}
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

<div class="modal fade" id="EditModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Campaign</h4>
			</div>
			{!! Form::open(['route' => 'node.campaign.edit', 'id' => 'campaign-edit-form']) !!}
			<input type="hidden" name="id" id="id" value="{!! old('id') !!}">

			<div class="modal-body">
				<div class="box-body">
                    <div class="form-group {!! \ViewHelper::showHasError('name') !!}">
			            {!! Form::Label('name', 'Campaign Name', ['class' => '  ']) !!}
			            {!! Form::text('name', 
			                old('name'),
			                ['class' => 'form-control', 'id' =>'name', 'placeholder' => 'The Campaign Name'] ) !!}    
			            
			            {!! \ViewHelper::showErrors('name') !!}
			        </div>
			         <div class="form-group">
			            {!! Form::Label('questions', 'Number of Questions', ['class' => '  ']) !!}
			           <h5 id='questions'>
			           </h5>    
			            
			          
			        </div>
			         <div class="form-group {!! \ViewHelper::showHasError('description') !!}">
			            {!! Form::Label('description', 'Campaign Description', ['class' => '  ']) !!}
			            {!! Form::textarea('description', 
			                old('description'),
			                ['class' => 'form-control', 'id'=>'description', 'placeholder' => 'Campaign Description'] ) !!}    
			            
			            {!! \ViewHelper::showErrors('description') !!}
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/campaign_dt_ajax') }}",
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

	$(document).on('click', '.edit_btn', function(){
		console.log($(this).attr('data-meta'))
		var data = JSON.parse($(this).attr('data-meta'))
		$('#campaign-edit-form').find('#name').val($(this).attr('data-name'))
		$('#campaign-edit-form').find('#id').val(data[0])
		$('#campaign-edit-form').find('#questions').html(data[3])
		$('#campaign-edit-form').find('#description').val(data[2])
		var string = '.typevalue_0';

		if($.inArray(data[3], ['Checkbox', 'Dropdown', 'Multiselect', 'Radio']) != -1){
			$('#campaign-edit-form').find(string).show()
		}else{
			$('#campaign-edit-form').find(string).hide()
		}
	})
});
</script>
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