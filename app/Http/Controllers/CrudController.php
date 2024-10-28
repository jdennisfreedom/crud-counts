<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            GROUP BY OBJECT_SCHEMA, OBJECT_NAME
            ORDER BY $orderBy DESC
        ");

        return view('crud_counts', ['crudCounts' => $results]);
    }
}
