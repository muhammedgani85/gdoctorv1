@extends('layouts/contentNavbarLayout')
@section('title', 'Upload Stock Spreadsheet')
@section('content')
<div class="container mt-4">
    <h3>Upload Stock Spreadsheet</h3>
    <form action="{{ route('stock.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Select Excel/CSV File</label>
            <input type="file" name="file" id="file" class="form-control" required accept=".xlsx,.csv">
        </div>
        <button class="btn btn-success">Upload</button>
        <a href="/samples/stock_sample.xlsx" class="btn btn-outline-secondary ms-2" download>Download Sample File</a>
    </form>
</div>
@endsection
