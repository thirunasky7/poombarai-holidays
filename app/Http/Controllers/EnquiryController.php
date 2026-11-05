<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Support\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EnquiryController extends Controller
{
    public function store(Request $request, SiteSettings $settings)
    {
        if ($request->filled('website')) {
            return back()->with('enquiry_ignored', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'phone' => ['required', 'regex:/^[0-9+\s-]{8,20}$/'],
            'service_type' => ['required', 'in:room,car,package,general'],
            'service_name' => ['nullable', 'string', 'max:120'],
            'destination' => ['nullable', 'string', 'max:120'],
            'travel_date' => ['nullable', 'date'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:12'],
            'requirements' => ['nullable', 'string', 'max:500'],
            'contact_method' => ['required', 'in:whatsapp,phone,email'],
        ]);

        $data['children'] = $data['children'] ?? 0;
        $data['reference'] = $this->reference();
        $data['status'] = 'new';

        $enquiry = Enquiry::create($data);
        $site = $settings->all();
        $mailSent = $this->notify($enquiry, $site);

        return back()->with('enquiry', [
            'reference' => $enquiry->reference,
            'whatsapp' => $enquiry->whatsappUrl($site['whatsapp_number'] ?? '', $site['business_name'] ?? 'the host'),
            'mail_sent' => $mailSent,
        ]);
    }

    private function reference(): string
    {
        do {
            $reference = 'ENQ-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (Enquiry::where('reference', $reference)->exists());

        return $reference;
    }

    private function notify(Enquiry $enquiry, array $site): bool
    {
        $sent = false;
        $admin = $site['email'] ?? '';
        $body = $enquiry->whatsappText($site['business_name'] ?? 'the host');

        try {
            if (filter_var($admin, FILTER_VALIDATE_EMAIL)) {
                Mail::raw($body, function ($message) use ($admin, $enquiry) {
                    $message->to($admin)->subject('New enquiry '.$enquiry->reference);
                });
                $sent = true;
            }
            if ($enquiry->email) {
                Mail::raw(
                    "Hello {$enquiry->name},\n\nWe saved enquiry {$enquiry->reference}. We will reply by {$enquiry->contact_method}. This is not a confirmed booking, and no payment is taken on the website.\n",
                    function ($message) use ($enquiry) {
                        $message->to($enquiry->email)->subject('We received enquiry '.$enquiry->reference);
                    }
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Enquiry email failed', [
                'reference' => $enquiry->reference,
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        return $sent;
    }
}
