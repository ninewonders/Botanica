@extends('layouts.app')

@section('content')
  <h1>Create Product</h1>

  <form action="{{ route('products.store') }}" method="POST">
    @csrf
    <div>
      <label for="name">Name</label>
      <input type="text" name="name" id="name" required>
    </div>
    <div>
      <label for="description">Description</label>
      <textarea name="description" id="description" required></textarea>
    </div>
    <div>
      <label for="price">Price</label>
      <input type="number" name="price" id="price" step="0.01" required>
    </div>
    <button type="submit">Create</button>
  </form>
@endsection
