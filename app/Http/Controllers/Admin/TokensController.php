<?php

namespace App\Http\Controllers\Admin;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TokenLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TokensController extends Controller
{
    /**
     * Display token management page
     */
    public function index()
{
    $users = \App\Models\User::with('tokenWallet')->orderBy('name')->get();

    $logs = \App\Models\TokenLog::with(['user', 'grantedBy'])
        ->latest()
        ->paginate(15);

    return view('admin.tokens', compact('users', 'logs'));
}

    /**
     * Grant tokens to user
     */
    public function grant(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::findOrFail($request->user_id);

            // Update balance directly on users table
            $token = \App\Models\Token::firstOrCreate(
                ['user_id' => $request->user_id],
                ['balance' => 0]
            );

            $token->increment('balance', $request->amount);

            // Create token log
            TokenLog::create([
                'user_id'   => $user->id,
                'granted_by'=> auth()->id(),
                'amount'    => $request->amount,
                'reason'    => $request->reason,
            ]);
        });

        return redirect()->back()->with('success', 'Tokens granted successfully.');
    }

public function export(): StreamedResponse
{
    $fileName = 'token_logs_' . now()->format('Ymd_His') . '.csv';

    $logs = \App\Models\TokenLog::with(['user', 'grantedBy'])
        ->latest()
        ->get();

    $response = new StreamedResponse(function () use ($logs) {

        $handle = fopen('php://output', 'w');

        // CSV Header
        fputcsv($handle, [
            'User ID',
            'User Name',
            'Granted By',
            'Amount',
            'Reason',
            'Date'
        ]);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->user_id,
                $log->user->name ?? '',
                $log->grantedBy->name ?? 'System',
                $log->amount,
                $log->reason,
                $log->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");

    return $response;
}
}