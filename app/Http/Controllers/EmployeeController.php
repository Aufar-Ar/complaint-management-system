<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // OLD: employee/dashboard.php top PHP block
    public function dashboard(Request $request)
    {
        $user_id = Auth::id();

        // OLD: $pdo->prepare("SELECT * FROM complaints WHERE employee_id = ? ORDER BY created_at DESC")
        $complaints = DB::table('complaints')
            ->where('employee_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // OLD: $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = FALSE ...")
        $notifications = DB::table('notifications')
            ->where('user_id', $user_id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // OLD: if (isset($_GET['clear_notifs']))
        if ($request->has('clear_notifs')) {
            DB::table('notifications')->where('user_id', $user_id)->update(['is_read' => true]);
            return redirect()->route('employee.dashboard');
        }

        return view('employee.dashboard', compact('complaints', 'notifications'));
    }

    // OLD: employee/submit.php GET
    public function showSubmit()
    {
        return view('employee.submit');
    }

    // OLD: employee/submit.php POST logic
    public function submit(Request $request)
    {
        $category    = $request->input('category');
        $description = trim($request->input('description'));
        $floor       = trim($request->input('floor'));
        $employee_id = Auth::id();
        $attachment_path = null;

        $valid_categories = ['Hardware', 'Software', 'Network', 'Other'];
        if (!in_array($category, $valid_categories)) {
            return back()->with('error', 'Invalid category selected.');
        }
        if (empty($description)) return back()->with('error', 'Please describe the problem.');
        if (empty($floor))       return back()->with('error', 'Please specify your floor.');

        // OLD: if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK)
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
            $ext = strtolower($file->getClientOriginalExtension());

            if (!in_array($ext, $allowed)) {
                return back()->with('error', 'Invalid file type. Only JPG, PNG, and PDF are allowed.');
            }

            // OLD: move_uploaded_file(..., '../uploads/' . $new_filename)
            // NEW: Laravel stores in storage/app/public/uploads/
            $filename = 'attach_' . uniqid() . '.' . $ext;
            $file->storeAs('uploads', $filename, 'public');
            $attachment_path = 'uploads/' . $filename;
        }

        // OLD: $pdo->prepare("INSERT INTO complaints (employee_id, category, description, floor, attachment) VALUES ...")
        DB::table('complaints')->insert([
            'employee_id'  => $employee_id,
            'category'     => $category,
            'description'  => $description,
            'floor'        => $floor,
            'attachment'   => $attachment_path,

        ]);

        return redirect()->route('employee.dashboard')->with('flash_success', 'Your ticket has been submitted successfully.');
    }
}
