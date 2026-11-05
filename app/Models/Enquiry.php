<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'reference', 'name', 'email', 'phone', 'service_type', 'service_name',
        'destination', 'travel_date', 'adults', 'children', 'requirements',
        'contact_method', 'status', 'internal_note',
    ];

    protected $casts = [
        'travel_date' => 'date',
    ];

    public function whatsappText(string $business): string
    {
        $lines = [
            'Hello '.$business.', I am '.$this->name.'.',
            'Enquiry '.$this->reference.'.',
            'Service: '.ucfirst($this->service_type).($this->service_name ? ' — '.$this->service_name : ''),
        ];
        if ($this->destination) {
            $lines[] = 'Destination: '.$this->destination;
        }
        if ($this->travel_date) {
            $lines[] = 'Travel date: '.$this->travel_date->format('j M Y');
        }
        $lines[] = 'Guests: '.$this->adults.' adults, '.$this->children.' children.';
        if ($this->requirements) {
            $lines[] = $this->requirements;
        }
        $lines[] = 'Please share availability. This chat opens only after I send it.';

        return implode("\n", $lines);
    }

    public function whatsappUrl(string $number, string $business): string
    {
        $digits = preg_replace('/\D+/', '', $number);

        return 'https://wa.me/'.$digits.'?text='.rawurlencode($this->whatsappText($business));
    }
}
