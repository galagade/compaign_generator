@extends("la.layouts.app")

@section("contentheader_title", "Campaign")
@section("contentheader_description", "Campaign Questions")
@section("section", "Campaign")
@section("sub_section", "Add Question")
@section("htmlheader_title", "Campaign Questions")

@section("headerElems")  

@endsection

@section("main-content")


{!! Form::open(['route' => 'node.campaign.store.questions', 'id' => 'organization-add-form']) !!}
{!! Form::hidden('campaign_id', $campaign->id) !!}
{!! Form::hidden('total', $campaign->questions) !!}
{!! Form::hidden('main_page', '1') !!}

	@for($i = 0; $i < $campaign->questions; $i++)
		<div class="box box-success">
			<div class="box-body">
				@php $d = $i; @endphp
				<h3>Question {{  ($d+1) }}</h3>
					
				<div class="form-group {!! \ViewHelper::showHasErrorArray('name',  $i) !!}">
			         {!! Form::Label('name', 'Question Name *', ['class' => '  ']) !!}
			         <input type="text" name="name[]"  value="{!! \ViewHelper::oldValue('name', $i) !!}" class='form-control' placeholder='Question Name e.g Firstname'>
			         {!! \ViewHelper::showErrorsArray('name', $i) !!} 
			    </div>
			    <div class="form-group {!! \ViewHelper::showHasErrorArray('type',  $i) !!}">
			         {!! Form::Label('name', 'Question type *', ['class' => '  ']) !!}
				        	<select class="form-control Questiontype" name="type[]" data-id="{{$i}}">
				        	<option value="">Select Question Type</option>
				        		@foreach($question_types as $type)
				        		<option value="{{ $type->name}}" {!! ( \ViewHelper::oldValue('type', $i) == $type->name ? 'selected' : '') !!} >{{ $type->name}}</option>
				        		@endforeach
				        	</select> 
			         
			         {!! \ViewHelper::showErrorsArray('type', $i) !!}
			     </div>
			    <div class="form-group {!! \ViewHelper::showHasErrorArray('values',  $i, true) !!}" id="typevalue_{{$i}}" {!! \ViewHelper::handleType('type', $i) !!}>
			         {!! Form::Label('name', 'Question type values *', ['class' => '  ']) !!}
				     {!! Form::text('values_'.$i, 
			                old('values_'.$i),
			                ['class' => 'form-control', 'placeholder' => 'Separate values with a comma'] ) !!}
			        {!! \ViewHelper::showErrorsArray('values', $i, true) !!}  
			   </div>
			</div>
		</div>
	@endfor
                   
	<div class="box box-success">			        
		<div class="box-body">
			{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	
	$("#user-add-form").validate({
		
	});
	var type_array =['Checkbox', 'Dropdown', 'Multiselect', 'Radio']
	$('.Questiontype').change(function(){
		console.log('changed')
		var string = '#typevalue_'+$(this).attr('data-id')
		console.log($.inArray($(this).val(), type_array))
		if($.inArray($(this).val(), type_array) != -1){
			$(string).show()
		}else{
			$(string).hide()
		}
		
	})
});
</script>

@endpush