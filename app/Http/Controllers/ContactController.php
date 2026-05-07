<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormSubmitted;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        if (! $this->verifyTurnstile($request->input('turnstile_token', ''))) {
            return back()->withInput()->withErrors(['turnstile_token' => __('ui.turnstile_failed')]);
        }

        $data = $request->validated();

        $submission = ContactSubmission::create([
            'name' => trim($data['first_name'].' '.$data['last_name']),
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => null,
            'message' => $data['message'],
            'agreed_terms' => true,
            'ip_address' => $request->ip(),
        ]);

        try {
            $adminEmail = SiteSetting::instance()->contact_email;
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ContactFormSubmitted($submission));
            }
        } catch (\Throwable) {
            // Submission is persisted; mail failure must not block the user.
        }

        return redirect()
            ->route('contact', ['locale' => app()->getLocale()])
            ->with('success', __('ui.contact_success'));
    }

    protected function verifyTurnstile(string $token): bool
    {
        if (blank(config('services.turnstile.secret_key'))) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret_key'),
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        return $response->successful() && $response->json('success') === true;
    }
}
