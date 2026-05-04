<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class IssueVideosController extends Controller
{
    public function edit(Issue $issue)
    {
        return view('dashboard.issues.videos.edit', compact('issue'));
    }

    public function update(Request $request, Issue $issue)
    {
        $request->validate([
            'story_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,ogv,mov', 'max:102400'],
            'ending_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,ogv,mov', 'max:102400'],
            'evidence' => ['nullable', 'array'],
            'evidence.*' => ['file', 'mimes:jpeg,png,gif,svg,webp,mp4,webm,ogg,ogv,mov', 'max:102400'],
        ]);

        if ($request->hasFile('story_video')) {
            $issue->clearMediaCollection('story_video');
            $issue->addMediaFromRequest('story_video')->toMediaCollection('story_video');
        }

        if ($request->hasFile('ending_video')) {
            $issue->clearMediaCollection('ending_video');
            $issue->addMediaFromRequest('ending_video')->toMediaCollection('ending_video');
        }

        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                if ($file && $file->isValid()) {
                    $issue->addMedia($file)->toMediaCollection('evidence');
                }
            }
        }

        return redirect()->route('dashboard.issues.videos.edit', $issue)->with('success', 'تم حفظ قسم الفيديوهات.');
    }

    public function destroyEvidence(Issue $issue, Media $media)
    {
        if (
            (int) $media->model_id !== (int) $issue->getKey()
            || $media->model_type !== $issue->getMorphClass()
            || $media->collection_name !== 'evidence'
        ) {
            abort(404);
        }

        $media->delete();

        return redirect()->route('dashboard.issues.videos.edit', $issue)->with('success', 'تم حذف الملف من الأدلة.');
    }
}
