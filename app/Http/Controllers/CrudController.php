<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CrudController extends Controller
{
    public function getCrudCounts(Request $request)
    {
        $orderBy = $request->query('order_by', 'table_reads');


        $results = DB::select("
            SELECT OBJECT_SCHEMA, OBJECT_NAME,
                   SUM(`COUNT_READ`) as table_reads,
                   SUM(`COUNT_UPDATE`) as table_writes,
                   SUM(`COUNT_INSERT`) as table_inserts,
                   SUM(`COUNT_DELETE`) as table_deletes
            FROM performance_schema.table_io_waits_summary_by_table
            WHERE OBJECT_SCHEMA = 'freedom_portal'
            GROUP BY OBJECT_SCHEMA, OBJECT_NAME
            ORDER BY $orderBy DESC
        ");

        $previousCounts = Session::get('crud_counts', []);
        // store the different counts in the results array
        foreach ($results as $row) {
            $row->table_reads_diff = $row->table_reads - ($previousCounts[$row->OBJECT_SCHEMA . '.' . $row->OBJECT_NAME]['table_reads'] ?? 0);
            $row->table_writes_diff = $row->table_writes - ($previousCounts[$row->OBJECT_SCHEMA . '.' . $row->OBJECT_NAME]['table_writes'] ?? 0);
            $row->table_inserts_diff = $row->table_inserts - ($previousCounts[$row->OBJECT_SCHEMA . '.' . $row->OBJECT_NAME]['table_inserts'] ?? 0);
            $row->table_deletes_diff = $row->table_deletes - ($previousCounts[$row->OBJECT_SCHEMA . '.' . $row->OBJECT_NAME]['table_deletes'] ?? 0);
        }

        foreach ($results as $row) {
            $previousCounts[$row->OBJECT_SCHEMA . '.' . $row->OBJECT_NAME] = [
                'table_reads' => $row->table_reads,
                'table_writes' => $row->table_writes,
                'table_inserts' => $row->table_inserts,
                'table_deletes' => $row->table_deletes,
            ];
        }

        Session::put('crud_counts', $previousCounts);

        return view('crud_counts', ['crudCounts' => $results]);
    }
}
