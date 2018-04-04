@extends("la.layouts.app")

@section("contentheader_title", "Campaign")
@section("contentheader_description", "Campaign Questions Answers")
@section("section", "Campaign")
@section("sub_section", "Answers")
@section("htmlheader_title", "Campaign Questions Answers")

@section("headerElems")  
<a href="{{url(config('laraadmin.adminRoute') . '/campaign/'.$campaign->id)}}" class="btn btn-success btn-sm pull-right" >Go Back</a> 
@endsection

@section("main-content")

<div class="box box-success">
	<div class="box-header">
	    
		
		<!-- <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#FormModal">Filter</button> -->
		@if(!empty($filter))
		<a class="btn btn-danger btn-sm" href="{{url(config('laraadmin.adminRoute') . '/campaign/'.$campaign->id.'/questions/answers/1')}}">Clear Filter</a>
		@endif

	</div>
	<div class="box-body">
        <div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                     <h5>{{$campaign->name }}   Campaign</h5>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content" >
                    <div style="overflow-x:auto;">
                    	<table id="datatable-buttons" class="table table-striped table-bordered" >
                      <thead>
                        <tr>
                        <th>ID</th>
                         <?php $i=0; $t=0; $answers =[];?>
							@foreach( $listing_cols as $question )
								<th>{{ $question->label }}</th>
							 <?php $i++; $answers[] = $question->id;  ?>
							 @endforeach
                        </tr>
                      </thead>


                      <tbody>
                      <?php //$data = \ViewHelper::answers_count($campaign->id); $size =( ($data > 50) ? 50 : $data); ?>
	                      @php 

	                        $data_row =[];

							for($a=0; $a < sizeof($answers) ; $a++){
								$id = $answers[$a];
								$data = \ViewHelper::getAnswers($answers[$a]);
								
								foreach ($data as $key => $value) {
									$data_row[$key][] = $value->value;
								}
							}

	                      @endphp
		                     @foreach($data_row as $key => $answers)
								<tr >
									<td>{{ ($key+1) }}</td>
									@for($i = 0; $i < sizeOf($answers); $i++)
									<td>{{ $answers[$i] }}</td>
									@endfor
								</tr>
							@endforeach
				
                      </tbody>
                    </table>
                    </div>
                    
                  </div>
                </div>
              </div>
</div>
	</div>
</div>

<div class="modal fade" id="FormModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Filter Form</h4>
			</div>
			@php $url_form = url(config('laraadmin.adminRoute') . '/campaign/'.$campaign->id.'/questions/answers/'.($current)); @endphp
			{!! Form::open(['url' => $url_form, 'id' => 'campaign-sample-form']) !!}
			 
			<div class="modal-body"> 
				<div class="row">
					@foreach( $listing_cols as $question )
					<div class="col-sm-4">
						<div class="form-group">
							<label>
								{{ $question->label }}
							</label>
							<input type="text" name="{{ $question->id}}" class="form-control" value="{{ (isset($filter[$question->id]) ? $filter[$question->id] : '' ) }}">
						</div>
					</div>
					
					@endforeach

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Filter', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>


@endsection

@push('styles')

   <link href="{{ asset('node-assets/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('node-assets/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('node-assets/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('node-assets/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('node-assets/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')

    <!-- Bootstrap -->
    <script src="{{ asset('node-assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('node-assets/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('node-assets/nprogress/nprogress.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('node-assets/iCheck/icheck.min.js') }}"></script>
 <script src="{{ asset('node-assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('node-assets/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
    <script src="{{ asset('node-assets/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('node-assets/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('node-assets/pdfmake/build/vfs_fonts.js') }}"></script>
 <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 1, 'asc' ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
    </script>
@endpush
<?php 
//old data
$sample =false;
if($sample){
	?>
<div style="overflow-x:auto; display: none">
			<table id="datatable-buttonss" class="table table-bordered">
			<thead>
			<tr class="success">
			<th><div class='Checkbox'> <input type="Checkbox" name="answers[]"></div></th>
				
				<?php $i=0; $t=0; $answers =[];?>
				@foreach( $listing_cols as $question )
					<th>{{ $question->label }}</th>
				 <?php $i++; $answers[] = $question->id;  ?>
				 @endforeach
			</tr>
			</thead>
			<tbody>
				@php $data = \ViewHelper::answers_count($campaign->id); $size =( ($data > 50) ? 50 : $data); @endphp
				
				@for ($i=0; $i <  $size; $i++) 
						<tr role="row" class="odd">
						<td class="sorting_1"><div class='Checkbox'> <input type="Checkbox" name="answers[]"></td>
						@for($a=0; $a < sizeof($answers) ; $a++)
							@php $id = $answers[$a]; $filter_value = (isset($filter[$id]) ? $filter[$id] : NULL ); @endphp
							<td>{{ \ViewHelper::getAnswer($answers[$a], $i, $current,  $filter_value) }}</td>
						@endfor
						</tr>
				@endfor
				
			</tbody>
			</table>
		</div>
		 <div class='row'>
                    <div class='col-sm-4'>
                           
                    </div>
                    <div class="col-sm-4">
                            <div class="text-center">
                                   
                            </div>
                    </div>
                    <div class="col-sm-4">
                            <div class='pull-right'>
                                   <ul class="pagination">
                                   	@if($current > 1)
                                   		 <li><a href="{{url(config('laraadmin.adminRoute') . '/campaign/'.$campaign->id.'/questions/answers/'.($current - 1))}}">Prev</a></li>
                                   @endif
									 
									  <li class="disabled"><a href="#">{{ $current }}</a></li>
									  <li><a href="{{url(config('laraadmin.adminRoute') . '/campaign/'.$campaign->id.'/questions/answers/'.($current + 1))}}">Next</a></li>
									</ul>
                            </div>
                    </div>
            </div>
	<?php
} ?>