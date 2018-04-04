@extends("la.layouts.app")

@section("contentheader_title", "Campaign")
@section("contentheader_description", "Campaign Upload")
@section("section", "Campaign")
@section("sub_section", "Upload Campaign")
@section("htmlheader_title", "Campaign Upload")

@section("headerElems")  

@endsection

@section("main-content")


{!! Form::open(['route' => 'node.campaign.upload', 'id' => 'organization-add-form', 'files'=>'true']) !!}

	<div class="box box-success">
			<div class="box-body"> 
				<p class="text">Please fill in the Campaign name and upload a valid CSV format file, please note that the data will be put to queue if the answers are over 50 </p>
				<p class="text">Note: the first line of the CSV file has to contain the question name, the rest of the question has to conatin the answers to the questions</p>
				<div class="form-group {!! \ViewHelper::showHasError('name') !!}">
			         {!! Form::Label('name', 'Campaign name', ['class' => '  ']) !!}
			         <input type="text" name="name"  class='form-control' value="{{ old('name') }}">
			        {!! \ViewHelper::showErrors('name') !!}
			    </div>
					
				<div class="form-group {!! \ViewHelper::showHasError('campaign_data_file') !!}">
			         {!! Form::Label('file', 'Campaign Data File', ['class' => '  ']) !!}
			      
			          {!! Form::file('campaign_data_file') !!}
			        {!! \ViewHelper::showErrors('campaign_data_file') !!}
			    </div>
			   
			</div>
		</div>   
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

	
});
</script>

@endpush