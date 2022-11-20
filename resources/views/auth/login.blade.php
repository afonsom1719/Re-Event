@extends('layouts.app')

@section('content')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & SignUp Form </title>
    <link href="{{ asset('css/pages/login.css') }}" rel="stylesheet">
</head>

<body>

    <div class="wrapper container">
        <div class="title-text">
            <div class="title login">Login Form</div>
            <div class="title signup">Signup Form</div>
        </div>

        <!-- Start Form Container -->
        <div class="form-container">
            <div class="slide-controls">
                <input type="radio" name="slider" id="login" checked>
                <input type="radio" name="slider" id="signup">
                <label for="login" class="slide login">Login</label>
                <label for="signup" class="slide signup"> Signup</label>
                <div class="slide-tab"></div>
            </div>

            <div class="form-inner">

                <!-- Start Login Form -->
                <form class="login" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                    <div class="field input-group">

                        <input type="email" class="form-control" placeholder="Email" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="error">
                            {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    <div class="field input-group input-icons">
                        <input class="form-control" placeholder="Password" id="password" type="password" name="password" required></input>
                        @if ($errors->has('password'))
                            <span class="error">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                    <div class="pass-link">
                        <a href="#">
                            Forgot password?
                        </a>
                    </div>
                    <div class="field input-group">
                        <input type="submit" value="Login">
                    </div>
                    <div class="signup-link">
                        Not a member? <a href="#">Signup now</a>
                    </div>
                </form>

                <!-- Start Signup Form -->
                <form method="POST" action="{{route('register')}}" class="signup">
                    {{ csrf_field() }}

                    <div class="field input-group">
                        <input class="form-control" placeholder="User Name" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                        @if ($errors->has('name'))
                            <span class="error">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                    </div>
                    <div class="field input-group">
                        <input  class="form-control" placeholder="Email Address" id="email" type="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="error">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    <div class="field input-group">
                        <input type="date" class="form-control" value="2023-11-16" min="1850-01-01" max="2022-11-16" id="birthdate" name="birthdate" value="{{old('birthdate')}}" required/>
                    </div>
                    <select class="form-select field input-group" id="gender" name="gender" value={{old('gender')}} aria-label="Default select example">
                        <option disabled="disabled" selected class="form-control">Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="O">Other</option>
                    </select>
                    <div class="field input-group">
                        <input class="form-control" placeholder="Password" id="password" type="password" name="password" required>
                        @if ($errors->has('password'))
                            <span class="error">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                    <div class="field input-group">
                        <input class="form-control" placeholder="Confirm password" id="password-confirm" type="password" name="password_confirmation" required>
                    </div>

                    <div class="mb-3">
                        <label for="formFile" class="form-label" style="padding-top:7px;"> Profile Photo </label>
                        <input class="form-control input-group" type="file" id="formFile">
                    </div>

                    <div class="field input-group">
                        <input type="submit" value="Signup">
                    </div>



                </form>
            </div>
        </div>
    </div>
</body>

</html>
@endsection