<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadPublicImageRequest;
use App\Models\Setting;
use App\Support\PublicMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PublicImageController extends Controller
{
    public function update(UploadPublicImageRequest $request, string $subject): RedirectResponse
    {
        $this->checkSubject($subject);
        $previous = Setting::get('media.'.$subject);
        $file = $request->file('image');
        abort_unless($file instanceof UploadedFile, 422);
        $stored = $file->store('', 'public_images');
        abort_if($stored === false, 500, 'The image could not be saved.');
        Setting::put('media.'.$subject, 'images/uploads/'.$stored, 'public images');
        $this->deletePreviousUpload($previous);

        return to_route('admin.settings.edit')->with('status', 'Image saved.');
    }

    public function destroy(string $subject): RedirectResponse
    {
        $this->checkSubject($subject);
        $previous = Setting::get('media.'.$subject);
        Setting::put('media.'.$subject, 'none', 'public images');
        $this->deletePreviousUpload($previous);

        return to_route('admin.settings.edit')->with('status', 'Image removed.');
    }

    public function restore(string $subject): RedirectResponse
    {
        $this->checkSubject($subject);
        $previous = Setting::get('media.'.$subject);
        Setting::put('media.'.$subject, null, 'public images');
        $this->deletePreviousUpload($previous);

        return to_route('admin.settings.edit')->with('status', 'Default image restored.');
    }

    private function checkSubject(string $subject): void
    {
        abort_unless(array_key_exists($subject, PublicMedia::SUBJECTS), 404);
    }

    private function deletePreviousUpload(?string $path): void
    {
        // Only this manager's random file names are removable; shared images stay.
        if ($path && preg_match('~^images/uploads/([a-zA-Z0-9]{40}\.(?:jpg|jpeg|png|webp))$~', $path, $matches)
            && ! Setting::query()->where('value', $path)->exists()) {
            Storage::disk('public_images')->delete($matches[1]);
        }
    }
}
