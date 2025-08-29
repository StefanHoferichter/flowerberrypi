<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class DBLock
{
    public static function run(string $lockName, int $ttlSeconds, callable $callback)
    {
        // Vorher: Alte Locks mit überschrittener TTL löschen
        DB::table('locks')
            ->where('name', $lockName)
            ->where('acquired_at', '<', Carbon::now()->subSeconds($ttlSeconds))
            ->delete();

        // Lock versuchen zu setzen
        try 
        {
            Log::info("acquiring '{$lockName}'");
            DB::table('locks')->insert([
                'name' => $lockName,
                'acquired_at' => now(),
            ]);
            Log::info("acquired '{$lockName}'");
        } 
        catch (QueryException $e) 
        {
            Log::info("Lock '{$lockName}' is already held.");
            return null;
        }

        try 
        {
            Log::info("calling secured code");
            return $callback(); // Code ausführen
        } 
        finally 
        {
            DB::table('locks')->where('name', $lockName)->delete(); // Lock freigeben
            Log::info("released '{$lockName}'");
        }
    }
}
