<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class StoryExportController extends Controller
{
    public function uploadWebm(Request $request)
    {
        $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/webm', 'max:51200'], // 50MB
        ]);

        $id = (string) Str::uuid();

        $dir = 'exports';
        Storage::disk('local')->makeDirectory($dir);

        $inName = "story_in_{$id}.webm";

        // Guardar en storage/app/exports
        $path = $request->file('video')->storeAs($dir, $inName, 'local');

        // ✅ Validación extra (para evitar tu error de "No such file")
        if (!$path || !Storage::disk('local')->exists($path)) {
            return response()->json([
                'error' => 'upload_failed',
                'details' => 'No se pudo guardar el archivo en storage/app',
            ], 500);
        }

        return response()->json([
            'ok' => true,
            'id' => $id,
        ]);
    }

    public function downloadMp4(string $id)
    {
        $dir = 'exports';
        $inRel  = "{$dir}/story_in_{$id}.webm";
        $outRel = "{$dir}/story_out_{$id}.mp4";

        if (!Storage::disk('local')->exists($inRel)) {
            return response()->json([
                'error' => 'input_not_found',
                'details' => $inRel,
            ], 404);
        }

        $inAbs  = Storage::disk('local')->path($inRel);
        $outAbs = Storage::disk('local')->path($outRel);

        // Convertir solo si no existe mp4 todavía
        if (!Storage::disk('local')->exists($outRel)) {
            $ffmpeg = env('FFMPEG_PATH', 'ffmpeg');

            // MP4 compatible Stories
            $cmd = [
                $ffmpeg,
                '-y',
                '-i', $inAbs,
              
                // ✅ Stories: 1080x1920, sin deformar, con padding, SAR=1
                '-vf', 'scale=1080:1920:force_original_aspect_ratio=decrease,' .
                       'pad=1080:1920:(ow-iw)/2:(oh-ih)/2,' .
                       'setsar=1,format=yuv420p',
              
                '-c:v', 'libx264',
                '-preset', 'veryfast',
                '-crf', '20',
                '-r', '30',
                '-movflags', '+faststart',
              
                // si no hay audio
                '-an',
              
                $outAbs,
              ];

            $process = new Process($cmd);
            $process->setTimeout(180); // 3 min
            $process->run();

            if (!$process->isSuccessful()) {
                return response()->json([
                    'error' => 'ffmpeg_failed',
                    'details' => $process->getErrorOutput() ?: $process->getOutput(),
                    'cmd' => $cmd,
                ], 500);
            }

            // ✅ asegurar que realmente se generó
            if (!file_exists($outAbs)) {
                return response()->json([
                    'error' => 'mp4_not_created',
                    'details' => $outRel,
                ], 500);
            }
        }

        return response()->download($outAbs, "sona-story.mp4", [
            'Content-Type' => 'video/mp4',
        ]);
    }
}