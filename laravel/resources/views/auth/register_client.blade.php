@extends('la.layouts.auth')

@section('htmlheader_title')
    Register
@endsection

@section('content')

    <body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url('/home') }}"><b>{{ LAConfigs::getByKey('sitename_part1') }} </b>Register</a>
        </div>

     <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info','error'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                @endforeach
            </div>
 

        <div class="register-box-body">
            <p class="login-box-msg">Register</p>
            <form action="{{ url('/signup/post') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback  {!! \ViewHelper::showHasError('name') !!}">
                     {!! Form::Label('name', 'Full Name', ['class' => '  ']) !!}
                    <input type="text" class="form-control" placeholder="Full name" name="name" value="{{ old('name') }}"/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    {!! \ViewHelper::showErrors('name') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showHasError('email') !!}">
                     {!! Form::Label('name', 'Email Address', ['class' => '  ']) !!}
                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    {!! \ViewHelper::showErrors('email') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showHasError('gender') !!}">
                     {!! Form::Label('name', 'Gender', ['class' => '  ']) !!}
                    <select class="form-control" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" {{ (old('gender') == 'male' ? "selected='true'": '' )}}>Male</option>
                        <option value="female" {{ (old('gender') == 'female' ? "selected='true'": '' )}}>Female</option>
                    </select>
                    
                    {!! \ViewHelper::showErrors('gender') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showHasError('mobile') !!}">
                    {!! Form::Label('name', 'Mobile Number', ['class' => '  ']) !!}
                    <input type="text" class="form-control" placeholder="mobile" name="mobile" value="{{ old('mobile') }}"/>
                    {!! \ViewHelper::showErrors('mobile') !!}
                </div>
                 <div class="form-group has-feedback {!! \ViewHelper::showHasError('address') !!}">
                    {!! Form::Label('name', 'Physical Address', ['class' => '  ']) !!}
                    <input type="text" class="form-control" placeholder="Physical Address" name="address" value="{{ old('address') }}"/>
                    {!! \ViewHelper::showErrors('address') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showHasError('city') !!}">
                    {!! Form::Label('name', 'City Name', ['class' => '  ']) !!}
                    <input type="text" class="form-control" placeholder="city" name="city" value="{{ old('city') }}"/>
                    {!! \ViewHelper::showErrors('city') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showHasError('date_birth') !!}">
                    {!! Form::Label('name', 'Date of Birth', ['class' => '  ']) !!}
                    <input type="date" class="form-control" placeholder="date_birth" name="date_birth" value="{{ old('date_birth') }}"/>
                    {!! \ViewHelper::showErrors('date_birth') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showHasError('about') !!}">
                    {!! Form::Label('name', 'About', ['class' => '  ']) !!}
                    <textarea name="about" class="form-control"> {{ old('about') }} </textarea>
                    {!! \ViewHelper::showErrors('about') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showErrors('password') !!}">
                    <input type="password" class="form-control" placeholder="Password" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                     {!! \ViewHelper::showErrors('password') !!}
                </div>
                <div class="form-group has-feedback {!! \ViewHelper::showErrors('password_confirmation') !!}">
                    <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation"/>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                     {!! \ViewHelper::showErrors('password_confirmation') !!}
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> I agree to the terms
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div><!-- /.col -->
                </div>
            </form>

            @include('auth.partials.social_login')
            <hr>
            <center><a href="{{ url('/login') }}" class="text-center">Login</a></center>
        </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    @include('la.layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

@endsection
