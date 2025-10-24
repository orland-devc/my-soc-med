<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class AccountController extends Controller
{
    public function showExportForm()
    {
        return view('settings.account-export');
    }

    public function export(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password.']);
        }

        // 1️⃣ Create temporary base export folder
        $base = storage_path('app/exports/user_'.$user->id.'_'.Str::random(5));
        File::ensureDirectoryExists($base);

        // --- HELPER FUNCTION TO SAVE JSON ---
        $saveJson = function ($filename, $data) use ($base) {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            File::put($base.'/'.$filename, $json);
        };

        // 2️⃣ Export user info
        $saveJson('user-info.json', $user->toArray());

        // 3️⃣ Export posts (with relationships)
        $posts = $user->posts()->with(['attachments', 'comments', 'likes'])->get();
        $saveJson('posts.json', $posts);

        // 4️⃣ Export comments
        $comments = $user->comments()->with('post')->get();
        $saveJson('comments.json', $comments);

        // 5️⃣ Export likes
        $likes = $user->likes()->with('post')->get();
        $saveJson('likes.json', $likes);

        // 6️⃣ Export reposts
        $reposts = $user->reposts()->with('post')->get();
        $saveJson('reposts.json', $reposts);

        // 7️⃣ Export messages folder
        $messageBase = $base.'/messages';
        File::ensureDirectoryExists($messageBase);

        // Example assumes you have a messages() relation:
        // e.g. $user->messages() returns all sent messages (with receiver_id)
        $messages = $user->messages()
            ->with(['receiver', 'sender'])
            ->orderBy('created_at')
            ->get()
            ->groupBy('receiver_id'); // or adjust depending on your schema

        foreach ($messages as $receiverId => $msgs) {
            $saveJson('messages/'.$receiverId.'message.json', $msgs);
        }

        // 8️⃣ Copy attachments
        $attachments = $user->posts()->with('attachments')->get()->pluck('attachments')->flatten();
        foreach ($attachments as $att) {
            $path = $att->path ?? $att->file_location ?? null;
            if ($path && Storage::disk('public')->exists($path)) {
                $destDir = $base.'/attachments';
                File::ensureDirectoryExists($destDir);
                @copy(Storage::disk('public')->path($path), $destDir.'/'.basename($path));
            }
        }

        // 9️⃣ Create ZIP file
        $zipPath = storage_path('app/exports/export_'.$user->id.'_'.now()->format('Ymd_His').'.zip');
        $zip = new \ZipArchive;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            File::deleteDirectory($base);

            return back()->withErrors(['export' => 'Could not create zip file.']);
        }

        foreach (File::allFiles($base) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());
            $zip->addFile($file->getRealPath(), $relative);
        }

        $zip->close();
        File::deleteDirectory($base);

        // 🔟 Download the ZIP
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function testZip()
    {
        $base = storage_path('app/exports/test_zip');
        \Illuminate\Support\Facades\File::ensureDirectoryExists($base);

        $testFile = $base.'/hello.txt';
        \Illuminate\Support\Facades\File::put($testFile, 'This is a test.');

        $zipPath = storage_path('app/exports/test.zip');
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            dd('Could not create zip file at '.$zipPath);
        }

        // ✅ Check file really exists before adding
        if (! file_exists($testFile)) {
            dd('File does not exist: '.$testFile);
        }

        // ✅ Add manually
        $zip->addFile($testFile, 'hello.txt');
        $zip->close();

        return response()->download($zipPath);
    }

    public function deactivate(Request $request)
    {
        $request->validate(['password' => 'required']);
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password.']);
        }

        $user->update(['is_active' => false]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Your account has been deactivated. You can reactivate it by contacting support.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required']);
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password.']);
        }

        DB::transaction(function () use ($user) {
            $user->load('posts.attachments');
            foreach ($user->posts as $post) {
                foreach ($post->attachments as $att) {
                    if ($att->path && Storage::disk('public')->exists($att->path)) {
                        Storage::disk('public')->delete($att->path);
                    }
                }
            }

            $user->comments()->delete();
            $user->likes()->delete();
            $user->followers()->detach();
            $user->following()->detach();

            $user->posts()->delete();
            $user->reposts()->delete();
            $user->delete();
        });

        Auth::logout();

        return redirect('login')->with('status', 'Your account has been permanently deleted.');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
