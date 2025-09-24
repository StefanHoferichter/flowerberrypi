@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_diagnosis_menu')  
@endsection
@section('content')

        <h1>433 MHz Reader</h1>


		<div class="data-container">
            <div class="grid-item">
        	
        	<form class="" action="/sniff" target="_top" method="post" novalidate="">
                  @csrf
                  Time in s &nbsp;<input type="number" name="timeout" step="1" inputmode="numeric" pattern="\d*" value="{{ $timeout }}" required>
                    <button name="action" value="on" type="submit">Click</button>
    	          </form>
        	
        	<h2>Current</h2>
            <table border="0" cellpadding="5">
                @foreach ($output as $row)
                    <tr>
                                <td>{{ $row }}</td>
                    </tr>
                @endforeach
            </table>     

@endsection