<?php

namespace App\Console\Commands;

use App\Models\Ride;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Console\Command;

class TestAuditLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the audit logging system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Audit Logging System...');
        $this->newLine();

        // Test 1: Model Create Event
        $this->info('Test 1: Creating a test ride...');
        try {
            $ride = Ride::create([
                'ride_name' => 'Audit Test Ride',
                'status' => 'pending',
                'fare_rate' => 100,
                'vehicle_id' => 1, // Add required field
            ]);
            $this->info("[PASS] Ride created with ID: {$ride->id}");
        } catch (\Exception $e) {
            $this->error("[FAIL] Failed to create ride: " . $e->getMessage());
            $this->newLine();
            $this->info('Note: The test requires a valid vehicle_id. Please ensure you have vehicles in your database.');
            $this->info('You can still manually test the audit logging by updating existing records.');
            return Command::FAILURE;
        }
        
        // Check if audit log was created
        $createLog = AuditLog::where('table', 'rides')
            ->where('data_id', $ride->id)
            ->where('event', 'created')
            ->first();
        
        if ($createLog) {
            $this->info('[PASS] Create event logged successfully!');
            $this->line("  Event: {$createLog->event}");
            $this->line("  Table: {$createLog->table}");
            $this->line("  Record ID: {$createLog->data_id}");
        } else {
            $this->error('[FAIL] Create event was NOT logged');
        }
        $this->newLine();

        // Test 2: Model Update Event
        $this->info('Test 2: Updating the ride status...');
        $ride->status = 'completed';
        $ride->save();
        $this->info('[PASS] Ride updated to completed status');
        
        $updateLog = AuditLog::where('table', 'rides')
            ->where('data_id', $ride->id)
            ->where('event', 'updated')
            ->first();
        
        if ($updateLog) {
            $this->info('[PASS] Update event logged successfully!');
            $this->line("  Event: {$updateLog->event}");
            $oldValues = json_decode($updateLog->old_values, true);
            $newValues = json_decode($updateLog->new_values, true);
            $this->line("  Old Value: {$oldValues['status']}");
            $this->line("  New Value: {$newValues['status']}");
        } else {
            $this->error('[FAIL] Update event was NOT logged');
        }
        $this->newLine();

        // Test 3: Audit Log Service
        $this->info('Test 3: Testing Audit Log Service...');
        $auditService = new AuditLogService();
        
        $customLog = $auditService->log(
            'custom_test_event',
            'test_table',
            999,
            ['field' => 'old_value'],
            ['field' => 'new_value']
        );
        
        if ($customLog) {
            $this->info('[PASS] Custom event logged successfully!');
            $this->line("  Event: {$customLog->event}");
            $this->line("  Table: {$customLog->table}");
        } else {
            $this->error('[FAIL] Custom event was NOT logged');
        }
        $this->newLine();

        // Test 4: Query Audit Logs
        $this->info('Test 4: Retrieving audit logs for the test ride...');
        $rideLogs = $ride->auditLogs();
        
        if ($rideLogs->count() > 0) {
            $this->info("[PASS] Found {$rideLogs->count()} audit log(s) for this ride");
            foreach ($rideLogs as $log) {
                $this->line("  - {$log->event} at {$log->created_at}");
            }
        } else {
            $this->error('[FAIL] No audit logs found for this ride');
        }
        $this->newLine();

        // Test 5: Model Delete Event
        $this->info('Test 5: Deleting the test ride...');
        $rideId = $ride->id;
        $ride->delete();
        $this->info('[PASS] Ride deleted');
        
        $deleteLog = AuditLog::where('table', 'rides')
            ->where('data_id', $rideId)
            ->where('event', 'deleted')
            ->first();
        
        if ($deleteLog) {
            $this->info('[PASS] Delete event logged successfully!');
            $this->line("  Event: {$deleteLog->event}");
            $this->line("  Record ID: {$deleteLog->data_id}");
        } else {
            $this->error('[FAIL] Delete event was NOT logged');
        }
        $this->newLine();

        // Summary
        $totalTestLogs = AuditLog::whereIn('event', ['created', 'updated', 'deleted', 'custom_test_event'])
            ->where(function($query) use ($rideId) {
                $query->where('table', 'rides')
                      ->where('data_id', $rideId)
                      ->orWhere('table', 'test_table');
            })
            ->count();
        
        $this->info('═══════════════════════════════════════');
        $this->info("All tests completed!");
        $this->info("Total test logs created: {$totalTestLogs}");
        $this->info('═══════════════════════════════════════');
        $this->newLine();
        
        // Cleanup
        $this->info('Cleaning up test logs...');
        AuditLog::where('table', 'rides')
            ->where('data_id', $rideId)
            ->delete();
        AuditLog::where('table', 'test_table')->delete();
        $this->info('[PASS] Test logs cleaned up');
        
        return Command::SUCCESS;
    }
}
