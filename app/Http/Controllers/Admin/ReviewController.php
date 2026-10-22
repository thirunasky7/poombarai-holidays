<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', ['reviews' => Review::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.reviews.form', ['review' => new Review(['stars' => 5, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $review = Review::create($this->payload($request));

        return redirect()->route('admin.reviews.edit', $review)->with('status', 'Review saved.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.form', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $review->update($this->payload($request));

        return redirect()->route('admin.reviews.edit', $review)->with('status', 'Review saved.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('status', 'Review removed.');
    }

    private function payload(Request $request): array
    {
        $data = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
            'detail' => ['nullable', 'string', 'max:160'],
            'quote' => ['required', 'string', 'max:800'],
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
