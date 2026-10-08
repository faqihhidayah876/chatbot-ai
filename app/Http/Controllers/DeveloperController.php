<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ApiKey;
use App\Models\ApiUsageLog;

class DeveloperController extends Controller
{
    /**
     * Halaman developer portal.
     */
    public function index()
    {
        $userId = Auth::id();
        
        $apiKeys = ApiKey::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalRequests = $apiKeys->sum('usage_total');
        $activeKeys = $apiKeys->where('is_active', true)->count();
        $todayRequests = $apiKeys->sum('usage_today');
        
        $keyIds = $apiKeys->pluck('id');
        $recentUsage = ApiUsageLog::whereIn('api_key_id', $keyIds)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartData[] = $recentUsage->get($date)->count ?? 0;
        }
        
        return view('developer.index', compact(
            'apiKeys',
            'totalRequests',
            'activeKeys',
            'todayRequests',
            'chartLabels',
            'chartData'
        ));
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'daily_limit' => 'nullable|integer|min:10|max:10000',
            ]);
            
            $userId = Auth::id();
            
            $existingKeys = ApiKey::where('user_id', $userId)->count();
            if ($existingKeys >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 5 API key per akun. Hapus salah satu dulu.',
                ], 400);
            }
            
            $name = $request->input('name');
            $dailyLimit = (int) $request->input('daily_limit', 100);
            
            [$plainKey, $apiKey] = ApiKey::generate($userId, $name, $dailyLimit);
            
            return response()->json([
                'success' => true,
                'plain_key' => $plainKey,
                'api_key' => [
                    'id' => $apiKey->id,
                    'name' => $apiKey->name,
                    'masked' => $apiKey->masked_key,
                    'daily_limit' => $apiKey->daily_limit,
                ],
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Generate API key error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate API key. Coba lagi.',
            ], 500);
        }
    }
    
    public function toggle($id)
    {
        try {
            $apiKey = ApiKey::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $apiKey->is_active = !$apiKey->is_active;
            $apiKey->save();
            
            return response()->json([
                'success' => true,
                'is_active' => $apiKey->is_active,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status key.',
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $apiKey = ApiKey::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            Log::info('API key deleted', [
                'user_id' => Auth::id(),
                'key_name' => $apiKey->name,
                'key_id' => $apiKey->id,
            ]);
            
            $apiKey->delete();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus API key.',
            ], 500);
        }
    }
    
    public function docs()
    {
        return view('docs.api');
    }
}
