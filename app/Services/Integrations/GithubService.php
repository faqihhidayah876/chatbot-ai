<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GithubService
{
    /**
     * Fetch konten repo GitHub (structure + core files).
     * Moved from ChatController@fetchGithubRepoContent
     */
    public function fetchRepoContent(string $repoUrl, string $userPrompt = ""): string
    {
        try {
            $repoUrl = str_replace('.git', '', trim($repoUrl));
            $parts = explode('github.com/', $repoUrl);
            if (count($parts) < 2) return "SISTEM ERROR: Link GitHub tidak valid.";

            $repoPath = explode('/', $parts[1]);
            if (count($repoPath) < 2) return "SISTEM ERROR: Format repository salah.";

            $owner = $repoPath[0];
            $repo = $repoPath[1];

            $repoInfo = Http::withOptions(['verify' => config('services.ssl.ca_bundle'), 'timeout' => 10])
                ->withHeaders(['User-Agent' => 'SAHAJA-AI'])
                ->get("https://api.github.com/repos/{$owner}/{$repo}");
            if (!$repoInfo->successful()) {
                return "SISTEM ERROR: Server GitHub membatasi akses (Limit 60 request/jam). Mohon istirahat sejenak dan coba lagi nanti.";
            }

            $defaultBranch = $repoInfo->json()['default_branch'] ?? 'main';

            $treeUrl = "https://api.github.com/repos/{$owner}/{$repo}/git/trees/{$defaultBranch}?recursive=1";
            $treeResponse = Http::withOptions(['verify' => config('services.ssl.ca_bundle'), 'timeout' => 15])
                ->withHeaders(['User-Agent' => 'SAHAJA-AI'])
                ->get($treeUrl);
            if (!$treeResponse->successful()) return "SISTEM ERROR: Gagal membaca struktur folder GitHub.";

            $files = $treeResponse->json()['tree'] ?? [];
            $blockedFolders = ['vendor/', 'node_modules/', 'storage/', 'public/build/', '.git/', 'tests/'];

            $treeMap = "📂 STRUKTUR FOLDER PROJECT:\n";
            $coreFiles = [];
            $priorityFiles = [];

            $cleanPrompt = preg_replace('/[^a-zA-Z0-9]/', ' ', strtolower($userPrompt));
            $userWords = array_filter(explode(' ', $cleanPrompt), function ($word) {
                return strlen($word) >= 3;
            });

            foreach ($files as $file) {
                if ($file['type'] !== 'blob') continue;
                $path = $file['path'];
                $isBlocked = false;
                foreach ($blockedFolders as $blocked) {
                    if (Str::startsWith($path, $blocked)) {
                        $isBlocked = true;
                        break;
                    }
                }
                if ($isBlocked) continue;

                $extension = pathinfo($path, PATHINFO_EXTENSION);
                if (Str::endsWith($path, '.blade.php')) $extension = 'blade.php';

                if (in_array(strtolower($extension), ['php', 'blade.php', 'js', 'jsx', 'ts', 'tsx', 'css', 'json', 'md', 'kt', 'java', 'xml', 'gradle', 'swift', 'dart', 'yaml'])) {
                    $pathLower = strtolower($path);
                    $treeMap .= "- {$path}\n";

                    if (in_array(basename($pathLower), ['readme.md', 'routes/web.php', 'composer.json', 'package.json', 'build.gradle', 'build.gradle.kts', 'androidmanifest.xml', 'pubspec.yaml'])) {
                        $coreFiles[] = $path;
                    }

                    foreach ($userWords as $word) {
                        if (strpos($pathLower, $word) !== false) {
                            $priorityFiles[] = $path;
                            break;
                        }
                    }
                }
            }

            if (strlen($treeMap) > 3000) {
                $treeMap = substr($treeMap, 0, 3000) . "\n... [STRUKTUR LAINNYA DISINGKAT]";
            }

            $filesToFetch = array_merge($priorityFiles, $coreFiles);
            $filesToFetch = array_unique($filesToFetch);
            $filesToFetch = array_slice($filesToFetch, 0, 20);

            $megaContent = $treeMap . "\n\n📄 KODE DARI FILE YANG RELEVAN:\n\n";
            foreach ($filesToFetch as $filePath) {
                $rawUrl = "https://raw.githubusercontent.com/{$owner}/{$repo}/{$defaultBranch}/{$filePath}";
                $fileContent = Http::withOptions(['verify' => config('services.ssl.ca_bundle'), 'timeout' => 5])->get($rawUrl);

                if ($fileContent->successful()) {
                    $content = $fileContent->body();
                    if (strlen($content) > 45000) {
                        $content = substr($content, 0, 45000) . "\n... [KODE DIPOTONG UNTUK MENGHEMAT MEMORI]";
                    }
                    $megaContent .= "--- FILE: {$filePath} ---\n```\n{$content}\n```\n\n";
                }
            }

            return $megaContent;
        } catch (\Exception $e) {
            Log::error('GitHub Scanner Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'repo'    => $repoUrl ?? null,
            ]);
            return "SISTEM ERROR: Gagal membaca isi file dari repository GitHub.";
        }
    }
}
