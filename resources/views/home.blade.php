@extends('layouts.home_layout')

@section('content')

    <div class="container-fluid">
    <div class="row content">
        @include('partials.side_nav')

        <div class="col-sm-9">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h2><small>Appointments</small></h2>
            <hr>
            <form method="POST" action="{{ route('filter.data') }}">
                @csrf
                <div class="form-group col-md-4">
                    <select class="form-control form-control-lg" name="doctor_id">
                        <option value="" disabled selected>Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->api_id }}">{{ $doctor->name }}</option>
                        @endforeach
                        <option>Select Doctor</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <input type="date" class="form-control" name="date" >
                </div>
                <div class="form-group col-md-4">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Clinic</th>
                    <th>Doctor</th>
                    <th>Speciality</th>
                    <th>Patient </th>
                    <th>Booked Time</th>
                    <th>Booked Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($appointments as $appointment)
                    <tr class="{{ $appointment->status ? "" : "text-danger" }}">
                        <td>{{ $appointment->clinic->name }}</td>
                        <td>{{ $appointment->doctor->name }}</td>
                        <td>{{ $appointment->speciality->name }}</td>
                        <td>{{ $appointment->patient->name }}</td>
                        <td>{{ $appointment->booked_time }}</td>
                        <td>{{ $appointment->booked_date }}</td>
                        <td>{{ $appointment->status ? "Active" : "Canceled" }}</td>
                    </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection


