@extends('layouts.app')

@section('content')
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">HISP Data</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <th colspan="2">
                            </th>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{$row['name']}}</td>
                                <td>
                                    <table style="width:100%">
                                        <thead>
                                            <th></th>
                                            @foreach($headers as $header)
                                            <th>
                                                {{$header}}
                                            </th>
                                            @endforeach
                                        </thead>
                                        
                                        @foreach($row['dxDataWithYears'] as $dx)
                                        <tbody>
                                            <tr>
                                                <td>{{$dx['name']}}</td>
                                                @foreach($dx['values'] as $value)
                                                <td>{{$value[0]}}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                        @endforeach
                                    </table>

                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection