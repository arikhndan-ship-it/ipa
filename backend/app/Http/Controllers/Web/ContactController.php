<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // Support locale via query parameter for external links (e.g. from mobile app)
        if ($request->has('locale') && in_array($request->locale, ['en', 'ckb'])) {
            session()->put('locale', $request->locale);
            app()->setLocale($request->locale);
        }

        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Save to database
        Contact::create([
            'name' => $validated['name'] ?? '',
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Send email logic here
        // Mail::to('contact@khandan.com')->send(...)

        return back()->with('success', __('messages.message_sent'));
    }
}
