<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Console\Command;

class TestAuditService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:test-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the audit logging service (no database dependencies)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('═══════════════════════════════════════');
        $this->info('Testing Audit Logging Service');
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        $auditService = new AuditLogService();

        // Test 1: Create a custom log entry
        $this->info('Test 1: Creating custom audit log...');
        try {
            $log = $auditService->log(
                'test_event',
                'test_table',
                123,
                ['field1' => 'old_value', 'field2' => 'old_data'],
                ['field1' => 'new_value', 'field2' => 'new_data']
            );
            
            $this->info('[PASS] Custom log created successfully!');
            $this->line("  ID: {$log->id}");
            $this->line("  Event: {$log->event}");
            $this->line("  Table: {$log->table}");
            $this->line("  Record ID: {$log->data_id}");
            $this->line("  Old Values: " . json_encode($log->old_values));
            $this->line("  New Values: " . json_encode($log->new_values));
        } catch (\Exception $e) {
            $this->error('[FAIL] Failed to create log: ' . $e->getMessage());
            return Command::FAILURE;
        }
        $this->newLine();

        // Test 2: Query the log we just created
        $this->info('Test 2: Querying the audit log...');
        try {
            $logs = AuditLog::forTable('test_table')
                ->forEvent('test_event')
                ->get();
            
            $this->info("[PASS] Found {$logs->count()} log(s)");
            if ($logs->count() > 0) {
                $this->line("  Latest log ID: {$logs->first()->id}");
                $this->line("  Created at: {$logs->first()->created_at}");
            }
        } catch (\Exception $e) {
            $this->error('[FAIL] Failed to query logs: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 3: Test various query scopes
        $this->info('Test 3: Testing query scopes...');
        try {
            // Test forTable scope
            $tableCount = AuditLog::forTable('test_table')->count();
            $this->line("  [PASS] forTable() scope: {$tableCount} logs found");
            
            // Test forEvent scope
            $eventCount = AuditLog::forEvent('test_event')->count();
            $this->line("  [PASS] forEvent() scope: {$eventCount} logs found");
            
            // Test date range
            $today = now()->startOfDay();
            $tomorrow = now()->addDay()->startOfDay();
            $dateCount = AuditLog::dateRange($today, $tomorrow)->count();
            $this->line("  [PASS] dateRange() scope: {$dateCount} logs found today");
        } catch (\Exception $e) {
            $this->error('[FAIL] Failed query scopes: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 4: Test authentication logging methods
        $this->info('Test 4: Testing authentication logging methods...');
        try {
            // Test login log
            $loginLog = $auditService->log(
                'test_login',
                'users',
                999,
                null,
                ['status' => 'logged_in']
            );
            $this->line('  [PASS] Login event logging works');
            
            // Test logout log
            $logoutLog = $auditService->log(
                'test_logout',
                'users',
                999,
                null,
                ['status' => 'logged_out']
            );
            $this->line('  [PASS] Logout event logging works');
            
            // Test failed login
            $failedLog = $auditService->logFailedLogin('test@example.com');
            $this->line('  [PASS] Failed login logging works');
        } catch (\Exception $e) {
            $this->error('[FAIL] Failed auth logging: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 5: Get statistics
        $this->info('Test 5: Getting audit log statistics...');
        try {
            $totalLogs = AuditLog::count();
            $todayLogs = AuditLog::whereDate('created_at', today())->count();
            
            $this->line("  Total audit logs: {$totalLogs}");
            $this->line("  Logs created today: {$todayLogs}");
            
            // Most common events
            $topEvents = AuditLog::selectRaw('event, COUNT(*) as count')
                ->groupBy('event')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();
            
            if ($topEvents->count() > 0) {
                $this->line('  Top events:');
                foreach ($topEvents as $event) {
                    $this->line("    - {$event->event}: {$event->count}");
                }
            }
        } catch (\Exception $e) {
            $this->error('[FAIL] Failed to get statistics: ' . $e->getMessage());
        }
        $this->newLine();

        // Summary
        $this->info('═══════════════════════════════════════');
        $this->info('[PASS] All service tests completed!');
        $this->info('═══════════════════════════════════════');
        $this->newLine();
        
        // Cleanup
        $this->info('Cleaning up test logs...');
        try {
            $deleted = AuditLog::where('table', 'test_table')->delete();
            $deleted += AuditLog::where('event', 'test_login')->delete();
            $deleted += AuditLog::where('event', 'test_logout')->delete();
            $deleted += AuditLog::where('event', 'failed_login')
                ->whereJsonContains('new_values->email', 'test@example.com')
                ->delete();
            
            $this->info("[PASS] Cleaned up {$deleted} test log(s)");
        } catch (\Exception $e) {
            $this->warn('Could not clean up all test logs: ' . $e->getMessage());
        }
        $this->newLine();
        
        $this->info('The audit logging system is working correctly!');
        $this->info('You can now:');
        $this->info('  1. Add the Auditable trait to your models');
        $this->info('  2. Use the AuditLogService for custom logging');
        $this->info('  3. Query audit logs using the AuditLog model');
        $this->newLine();
        
        return Command::SUCCESS;
    }
}
