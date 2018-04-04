<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
	@include('la.layouts.partials.htmlheader')
@show
<body class="{{ LAConfigs::getByKey('skin') }} {{ LAConfigs::getByKey('layout') }} @if(LAConfigs::getByKey('layout') == 'sidebar-mini') sidebar-collapse @endif" bsurl="{{ url('') }}" adminRoute="{{ config('laraadmin.adminRoute') }}">
<div class="wrapper">

	@include('la.layouts.partials.mainheader')

	@if(LAConfigs::getByKey('layout') != 'layout-top-nav')
		@include('la.layouts.partials.sidebar')
	@endif

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		@if(LAConfigs::getByKey('layout') == 'layout-top-nav') <div class="container"> @endif
		@if(!isset($no_header))
			@include('la.layouts.partials.contentheader')
		@endif
		
		<!-- Main content -->
		<section class="content {{ $no_padding or '' }}">
			<div class="flash-message">
			    @foreach (['danger', 'warning', 'success', 'info','error'] as $msg)
			        @if(Session::has('alert-' . $msg))
			            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
			        @endif
			    @endforeach
			</div>
			<!-- Your Page Content Here -->
			@yield('main-content')
		</section><!-- /.content -->

		@if(LAConfigs::getByKey('layout') == 'layout-top-nav') </div> @endif
	</div><!-- /.content-wrapper -->

	@include('la.layouts.partials.controlsidebar')

	@include('la.layouts.partials.footer')

</div><!-- ./wrapper -->

@include('la.layouts.partials.file_manager')

@section('scripts')
	@include('la.layouts.partials.scripts')
@show

</body>
</html>
