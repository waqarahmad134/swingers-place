<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportedUsersController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'resolved'); // pending, resolved, all
        
        // Sample data - in the future, this can come from a reports table
        $sampleReports = [
            [
                'id' => 1,
                'type' => 'Inappropriate Content',
                'status' => 'resolved',
                'reported_date' => '2024-11-19',
                'reported_user' => [
                    'name' => 'John Doe',
                    'email' => 'john.d@email.com',
                ],
                'reason' => 'Offensive profile content',
                'reported_by' => 'Jane Smith',
            ],
            [
                'id' => 2,
                'type' => 'Scam',
                'status' => 'resolved',
                'reported_date' => '2024-11-17',
                'reported_user' => [
                    'name' => 'Alex Wilson',
                    'email' => 'alex.w@email.com',
                ],
                'reason' => 'Asking for money',
                'reported_by' => 'Emma Taylor',
            ],
            [
                'id' => 3,
                'type' => 'Harassment',
                'status' => 'pending',
                'reported_date' => '2024-11-20',
                'reported_user' => [
                    'name' => 'Mike Johnson',
                    'email' => 'mike.j@email.com',
                ],
                'reason' => 'Sending inappropriate messages',
                'reported_by' => 'Sarah Brown',
            ],
            [
                'id' => 4,
                'type' => 'Fake Profile',
                'status' => 'pending',
                'reported_date' => '2024-11-21',
                'reported_user' => [
                    'name' => 'Chris Lee',
                    'email' => 'chris.l@email.com',
                ],
                'reason' => 'Using fake photos',
                'reported_by' => 'Lisa Anderson',
            ],
        ];
        
        // Filter by status
        if ($status !== 'all') {
            $sampleReports = array_filter($sampleReports, function($r) use ($status) {
                return $r['status'] === $status;
            });
        }
        
        // Count pending reports
        $pendingCount = count(array_filter($sampleReports, fn($r) => $r['status'] === 'pending'));
        
        return view('admin.reported-users.index', [
            'reports' => $sampleReports,
            'currentStatus' => $status,
            'pendingCount' => $pendingCount,
        ]);
    }
    
    public function review($id)
    {
        // TODO: Implement review functionality
        return redirect()->route('admin.reported-users.index')
            ->with('success', 'Report reviewed successfully!');
    }
}

