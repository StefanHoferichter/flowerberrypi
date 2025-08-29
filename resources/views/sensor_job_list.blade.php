@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('time_horizon_menu')
        <h1>Sensor Jobs</h1>

	<form action="/trigger_job" target="_top" method="post">
	        @csrf
			<table>
			 <tbody><tr><td>Adhoc:<input type="checkbox" id="adhoc" name="adhoc" value="false"></td><td><button dusk="job_submit" type="submit">Start</button></td></tr>
            </tbody></table>
		</form>


		<div class="data-container">
            <div class="grid-item">
        	<h2>List</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Status</th>
                        <th>Started</th>
                        <th>Ended</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($history as $job) 
               <tr>
           			<td><a  href="/job_details/{{$job->id}}">{{ $job->id }}</a></td>
	                <td><a  href="/job_details/{{$job->id}}">{{ $job->status }}</a></td>
               <td> {{ $job->created_at }} </td>
				 <td> {{ $job->updated_at  }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	

@endsection