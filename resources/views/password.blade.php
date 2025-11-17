@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_setup_menu')  
@endsection
@section('content')

        <h1>Password</h1>

     	@if(session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif


		<div class="data-container">
            <div class="grid-item">
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Enter Password</th>
                        <th>Confirm Password</th>
                        <th>Message</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($users as $user) 
                  <tr>
                 	<form method="post" action="/setup_password">        @csrf
                    <td>{{ $user->id }}</td>
	                <input type="hidden" name="id" value="{{ $user->id }}">
                	<td>{{ $user->name }}</td>
                	<td><input type="password" name="new_password"  value="" size="50"></td>
                	<td><input type="password" name="new_password_confirmation"  value="" size="50"></td>
                	<td>
                    	@error('new_password')
                            <p class="highlighted">{{ $message }}</p>
                        @enderror
                    </td>
                    <td><button name="action" value="on" type="submit">Save</button></td>
                 	</form>
                  </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	</div>
        	

@endsection