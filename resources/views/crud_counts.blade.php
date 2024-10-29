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
    <button type="submit">Refresh</button>
</form>

<table border="1" id="crudTable">
    <thead>
    <tr>
        <th onclick="sortTable(0)">Schema</th>
        <th onclick="sortTable(1)">Table</th>
        <th onclick="sortTable(2)">Reads</th>
        <th onclick="sortTable(3)">&#916; Reads</th>
        <th onclick="sortTable(4)">Writes</th>
        <th onclick="sortTable(5)">&#916; Writes</th>
        <th onclick="sortTable(6)">Inserts</th>
        <th onclick="sortTable(7)">&#916; Inserts</th>
        <th onclick="sortTable(8)">Deletes</th>
        <th onclick="sortTable(9)">&#916; Deletes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($crudCounts as $row)
        <tr>
            <td>{{ $row->OBJECT_SCHEMA }}</td>
            <td>{{ $row->OBJECT_NAME }}</td>
            <td>{{ number_format($row->table_reads) }}</td>
            <td>{{ number_format($row->table_reads_diff) }}</td>
            <td>{{ number_format($row->table_writes) }}</td>
            <td>{{ number_format($row->table_writes_diff) }}</td>
            <td>{{ number_format($row->table_inserts) }}</td>
            <td>{{ number_format($row->table_inserts_diff) }}</td>
            <td>{{ number_format($row->table_deletes) }}</td>
            <td>{{ number_format($row->table_deletes_diff) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Retrieve the selected sort option from local storage
        const selectedSort = localStorage.getItem('selectedSort');

        if (selectedSort) {
            document.getElementById('order_by').value = selectedSort;
        }

        // Store the selected sort option in local storage when the form is submitted
        document.getElementById('sortForm').addEventListener('submit', function() {
            const orderBy = document.getElementById('order_by').value;
            localStorage.setItem('selectedSort', orderBy);
        });
    });

    function sortTable(columnIndex) {
        const table = document.getElementById("crudTable");
        const rowCount = table.rows.length;
        let x, y, xContent, yContent;

        // display hourglass cursor
        document.body.style.cursor = 'wait';
        requestAnimationFrame(() => {
            setTimeout(() => {
                for (let i = 0; i < rowCount - 1; i++) {
                    for (let j = 0; j < rowCount - i - 1; j++) {
                        const row1 = table.rows[j].getElementsByTagName("TD");
                        const row2 = table.rows[j + 1].getElementsByTagName("TD");

                        x = row1[columnIndex];
                        y = row2[columnIndex];

                        if (x && y) {
                            xContent = parseInt(x.innerHTML.replace(/,/g, ''));
                            yContent = parseInt(y.innerHTML.replace(/,/g, ''));

                            if (xContent < yContent) {
                                table.rows[j].parentNode.insertBefore(table.rows[j + 1], table.rows[j]);
                            }
                        }
                    }
                }
                // revert cursor back to normal
                document.body.style.cursor = 'default';
            }, 0);
        });
    }
</script>
</body>
</html>
