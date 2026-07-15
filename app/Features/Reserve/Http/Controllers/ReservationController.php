<?php

declare(strict_types=1);

namespace App\Features\Reserve\Http\Controllers;

use App\Core\Audit\AuditLogger;
use App\Core\Authorization\Permission;
use App\Features\Reserve\Http\Requests\StoreReservationRequest;
use App\Features\Reserve\Http\Requests\UpdateReservationRequest;
use App\Features\Reserve\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReservationController extends Controller
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function index(): View
    {
        Gate::authorize(Permission::ReserveView->value);

        return view('reserve::index', [
            'todayCount' => Reservation::query()->whereDate('reserved_at', today())->where('status', '!=', Reservation::CANCELLED)->count(),
            'pendingCount' => Reservation::query()->where('status', Reservation::PENDING)->count(),
            'upcomingCount' => Reservation::query()->where('reserved_at', '>=', now())->where('status', '!=', Reservation::CANCELLED)->count(),
            'reservations' => Reservation::query()
                ->where('reserved_at', '>=', today())
                ->orderBy('reserved_at')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::ReserveManage->value);

        return view('reserve::form', ['reservation' => new Reservation(['status' => Reservation::PENDING])]);
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = Reservation::PENDING;

        $reservation = Reservation::query()->create($data);
        $this->audit->log('reservation.created', $reservation, ['customer' => $reservation->customer_name]);

        return redirect()->route('reserve.index')->with('status', '予約を登録しました。');
    }

    public function edit(Reservation $reservation): View
    {
        Gate::authorize(Permission::ReserveManage->value);

        return view('reserve::form', ['reservation' => $reservation]);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $reservation->update($request->validated());
        $this->audit->log('reservation.updated', $reservation, ['customer' => $reservation->customer_name]);

        return redirect()->route('reserve.index')->with('status', '予約を更新しました。');
    }

    public function confirm(Reservation $reservation): RedirectResponse
    {
        Gate::authorize(Permission::ReserveManage->value);

        $reservation->update(['status' => Reservation::CONFIRMED]);
        $this->audit->log('reservation.confirmed', $reservation);

        return redirect()->route('reserve.index')->with('status', '予約を確定しました。');
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        Gate::authorize(Permission::ReserveManage->value);

        $reservation->update(['status' => Reservation::CANCELLED]);
        $this->audit->log('reservation.cancelled', $reservation);

        return redirect()->route('reserve.index')->with('status', '予約をキャンセルしました。');
    }

    public function export(): StreamedResponse
    {
        Gate::authorize(Permission::ReserveView->value);

        $filename = 'reservations_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['日時', '顧客名', '電話', 'メール', '人数', '状態', 'メモ']);

            Reservation::query()
                ->orderBy('reserved_at')
                ->chunk(500, function ($reservations) use ($out): void {
                    foreach ($reservations as $reservation) {
                        fputcsv($out, [
                            $reservation->reserved_at->format('Y-m-d H:i'),
                            $reservation->customer_name,
                            $reservation->phone ?? '',
                            $reservation->email ?? '',
                            $reservation->party_size,
                            $reservation->statusLabel(),
                            $reservation->note ?? '',
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
