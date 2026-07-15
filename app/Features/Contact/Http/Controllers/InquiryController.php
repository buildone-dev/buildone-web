<?php

declare(strict_types=1);

namespace App\Features\Contact\Http\Controllers;

use App\Core\Authorization\Permission;
use App\Features\Contact\Models\Inquiry;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

final class InquiryController extends Controller
{
    public function index(): View
    {
        Gate::authorize(Permission::InquiryView->value);

        $q = trim((string) request('q'));

        return view('contact::admin', [
            'inquiries' => Inquiry::query()
                ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('company', 'like', "%{$q}%")->orWhere('contact_name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")))
                ->latest()->paginate(20)->withQueryString(),
        ]);
    }
}
