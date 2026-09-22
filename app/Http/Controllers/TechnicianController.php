<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TechnicianController extends Controller
{
    // OLD: technician/dashboard.php top PHP block
    public function dashboard(Request $request)
    {
        $user_id = Auth::id();

        // OLD: $pdo->query("SELECT COUNT(*) as total, SUM(...) ...")
        $stats = DB::table('complaints')->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
        ")->first();

        // OLD: $stmt_open
        $open_tickets = DB::table('complaints')
            ->join('users', 'complaints.employee_id', '=', 'users.id')
            ->select('complaints.*', 'users.username as employee_name')
            ->where('complaints.status', 'pending')
            ->orderBy('complaints.created_at', 'asc')
            ->get();

        // OLD: $stmt_my
        $my_tickets = DB::table('complaints')
            ->join('users', 'complaints.employee_id', '=', 'users.id')
            ->select('complaints.*', 'users.username as employee_name', 'users.phone_number')
            ->where('complaints.technician_id', $user_id)
            ->where('complaints.status', 'in_progress')
            ->orderBy('complaints.updated_at', 'desc')
            ->get();

        // OLD: $recent_stmt
        $recent_tickets = DB::table('complaints')
            ->join('users as e', 'complaints.employee_id', '=', 'e.id')
            ->leftJoin('users as t', 'complaints.technician_id', '=', 't.id')
            ->select('complaints.*', 'e.username as employee_name', 't.username as technician_name')
            ->orderByDesc('complaints.updated_at')
            ->limit(15)
            ->get();

        // OLD: $_GET['filter']
        $active_filter = $request->query('filter');
        $filtered_tickets = collect();

        if ($active_filter) {
            $query = DB::table('complaints')
                ->join('users as e', 'complaints.employee_id', '=', 'e.id')
                ->leftJoin('users as t', 'complaints.technician_id', '=', 't.id')
                ->select('complaints.*', 'e.username as employee_name', 't.username as technician_name')
                ->orderByDesc('complaints.updated_at');

            if ($active_filter !== 'total') {
                $query->where('complaints.status', $active_filter);
            }

            $filtered_tickets = $query->get();
        }

        return view('technician.dashboard', compact(
            'stats', 'open_tickets', 'my_tickets', 'recent_tickets', 'active_filter', 'filtered_tickets'
        ));
    }

    // OLD: technician/action.php
    public function action(Request $request)
    {
        $ticket_id    = $request->input('ticket_id');
        $action       = $request->input('action');
        $technician_id = Auth::id();

        $ticket = DB::table('complaints')->where('id', $ticket_id)->first();

        if ($ticket) {
            $employee_id = $ticket->employee_id;

            if ($action === 'claim' && $ticket->status === 'pending') {
                $updated = DB::table('complaints')
                    ->where('id', $ticket_id)
                    ->where('status', 'pending')
                    ->update(['status' => 'in_progress', 'technician_id' => $technician_id]);

                if ($updated) {
                    DB::table('notifications')->insert([
                        'user_id'    => $employee_id,
                        'message'    => "Your ticket #$ticket_id has been claimed and is now In Progress.",

                    ]);
                    return redirect()->route('technician.dashboard')->with('flash_success', "You have successfully claimed ticket #$ticket_id.");
                }
                return redirect()->route('technician.dashboard')->with('flash_error', 'Failed to claim ticket. Someone else may have grabbed it.');

            } elseif ($action === 'resolve') {
                $updated = DB::table('complaints')
                    ->where('id', $ticket_id)
                    ->where('technician_id', $technician_id)
                    ->update(['status' => 'resolved']);

                if ($updated) {
                    DB::table('notifications')->insert([
                        'user_id'    => $employee_id,
                        'message'    => "Good news! Your ticket #$ticket_id has been resolved.",

                    ]);
                    return redirect()->route('technician.dashboard')->with('flash_success', "Ticket #$ticket_id marked as resolved.");
                }
                return redirect()->route('technician.dashboard')->with('flash_error', 'Failed to resolve ticket.');
            }
        }

        return redirect()->route('technician.dashboard');
    }
}
