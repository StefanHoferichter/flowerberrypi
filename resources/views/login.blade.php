@extends('flowerberrypi')
 
@section('title', 'Home')
@section('submenu')
@include ('include_system_menu')  
@endsection

@section('content')


        <h1>Login</h1>


<form class="login-form" method="POST" action="/login">
    @csrf
    <table>
        <tr>
            <td><label for="email">Email</label></td>
            <td><input id="email" type="email" name="email" required autofocus autocomplete="username"></td>
        </tr>
        <tr>
            <td><label for="password">Password</label></td>
            <td><input id="password" type="password" name="password" required autocomplete="current-password"></td>
        </tr>
        <tr>
            <td></td>
            <td><button type="submit">Log in</button></td>
        </tr>
    </table>
</form>


@endsection