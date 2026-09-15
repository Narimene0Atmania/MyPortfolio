<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store a contact form submission and notify the owner by email.
     *
     * The form is submitted via fetch() from the contact.exe window so the
     * desktop state (open windows, z-order, etc.) never reloads.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contactMessage = ContactMessage::create($validated);

        try {
            Mail::raw(
                "New message from {$validated['name']} <{$validated['email']}>:\n\n{$validated['message']}",
                function ($mail) use ($validated) {
                    $mail->to(config('portfolio.contact_email'))
                        ->subject('New portfolio contact — '.$validated['name'])
                        ->replyTo($validated['email'], $validated['name']);
                }
            );
        } catch (\Throwable $e) {
            // Never fail the request over a mail transport issue — the
            // submission is already saved. Configure MAIL_* in .env to send
            // for real; until then this just logs.
            Log::warning('Contact form mail could not be sent: '.$e->getMessage());
        }

        return response()->json([
            'ok' => true,
            'message' => __('site.contact.status.success'),
        ], 201, [], JSON_UNESCAPED_SLASHES);
    }
}
