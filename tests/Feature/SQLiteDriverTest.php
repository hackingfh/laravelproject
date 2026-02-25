<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SQLiteDriverTest extends TestCase
{
    public function test_sqlite_driver_available()
    {
        $result = DB::connection('sqlite')->select('select 1 as one');
        $this->assertEquals(1, $result[0]->one ?? null);
    }
}
