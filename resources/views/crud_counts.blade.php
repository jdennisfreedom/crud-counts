<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operation Counts</title>
</head>
<body>
<h1>CRUD Ops by Table</h1>

<form method="GET" action="{{ url('/crud-counts') }}">
    <label for="order_by">Order By:</label>
    <select name="order_by" id="order_by">
        <option value="table_reads">Reads</option>
        <option value="table_writes">Writes</option>
        <option value="table_inserts">Inserts</option>
        <option value="table_deletes">Deletes</option>
    </select>
    <button type="submit">Sort</button>
</form>

<table border="1">
    <thead>
    <tr>
        <th>Schema</th>
        <th>Table</th>
        <th>Reads</th>
        <th>Writes</th>
        <th>Inserts</th>
        <th>Deletes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($crudCounts as $row)
        <tr>
            <td>{{ $row->OBJECT_SCHEMA }}</td>
            <td>{{ $row->OBJECT_NAME }}</td>
            <td>{{ number_format($row->table_reads) }}</td>
            <td>{{ number_format($row->table_writes) }}</td>
            <td>{{ number_format($row->table_inserts) }}</td>
            <td>{{ number_format($row->table_deletes) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Retrieve the selected sort option from local storage
        const selectedSort = localStorage.getItem('selectedSort');

        console.log(selectedSort);

        if (selectedSort) {
            document.getElementById('order_by').value = selectedSort;
        }

        // Store the selected sort option in local storage when the form is submitted
        document.getElementById('sortForm').addEventListener('submit', function() {
            const orderBy = document.getElementById('order_by').value;
            localStorage.setItem('selectedSort', orderBy);
        });
    });
</script>

</body>
</html>
