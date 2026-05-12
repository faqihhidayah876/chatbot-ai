<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\LlmDocument;
use Illuminate\Support\Facades\Auth;

class SahajaLlmController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Buat Workspace Default jika user belum punya
        $workspace = Workspace::firstOrCreate(
            ['user_id' => $userId, 'title' => 'Notebook Utama'],
            ['description' => 'Ruang kerja utama SAHAJA LLM']
        );

        $documents = $workspace->documents()->orderBy('created_at', 'desc')->get();

        return view('sahaja-llm', compact('workspace', 'documents'));
    }

    public function uploadDocument(Request $request)
    {
        try {
            $request->validate([
                'workspace_id' => 'required',
                'file_name' => 'required',
                'content' => 'required'
            ]);

            // Simpan teks PDF ke database
            $doc = LlmDocument::create([
                'workspace_id' => $request->workspace_id,
                'file_name' => $request->file_name,
                'content' => $request->content
            ]);

            return response()->json(['success' => true, 'doc' => $doc]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function deleteDocument($id)
    {
        try {
            $doc = LlmDocument::findOrFail($id);
            // Pastikan dokumen ini milik user yang sedang login
            $workspace = Workspace::where('id', $doc->workspace_id)->where('user_id', Auth::id())->first();

            if ($workspace) {
                $doc->delete();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
