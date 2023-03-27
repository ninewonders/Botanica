<!DOCTYPE html>
<html>
  <head>
    <title>Product List</title>
  </head>
  <body>
    <h1>Product List</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->description }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>
