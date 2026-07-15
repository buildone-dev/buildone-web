<?php

declare(strict_types=1);

namespace App\Features\Report\Http\Controllers;

use App\Core\Authorization\Permission;
use App\Features\Report\Http\Requests\StoreReportRequest;
use App\Features\Report\Models\Report;
use App\Features\Report\Services\ReportSummarizer;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReportController extends Controller
{
    public function index(): View
    {
        Gate::authorize(Permission::ReportView->value);

        return view('report::index', [
            'monthCount' => Report::query()->whereBetween('report_date', [today()->startOfMonth(), today()->endOfMonth()])->count(),
            'summarizedCount' => Report::query()->whereNotNull('summary')->count(),
            'totalCount' => Report::query()->count(),
            'reports' => Report::query()->with('author')->latest('report_date')->paginate(20),
        ]);
    }

    public function create(): View
    {
        Gate::authorize(Permission::ReportManage->value);

        return view('report::form', ['report' => new Report(['report_date' => today()])]);
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $report = Report::query()->create([
            ...$request->validated(),
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->route('report.show', $report)->with('status', '日報を登録しました。');
    }

    public function show(Report $report): View
    {
        Gate::authorize(Permission::ReportView->value);

        return view('report::show', ['report' => $report->load('author')]);
    }

    public function edit(Report $report): View
    {
        Gate::authorize(Permission::ReportManage->value);

        return view('report::form', ['report' => $report]);
    }

    public function update(StoreReportRequest $request, Report $report): RedirectResponse
    {
        $report->update($request->validated());

        return redirect()->route('report.show', $report)->with('status', '日報を更新しました。');
    }

    public function summarize(Report $report, ReportSummarizer $summarizer): RedirectResponse
    {
        Gate::authorize(Permission::ReportManage->value);

        $summary = $summarizer->summarize($report->body);

        if ($summary === null) {
            return redirect()->route('report.show', $report)
                ->with('summary_error', 'AI要約を生成できませんでした（APIキー未設定、または残高不足の可能性があります）。');
        }

        $report->update(['summary' => $summary]);

        return redirect()->route('report.show', $report)->with('status', 'AI要約を生成しました。');
    }

    public function export(): StreamedResponse
    {
        Gate::authorize(Permission::ReportView->value);

        $filename = 'reports_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['日付', '作成者', '本文', 'AI要約']);

            Report::query()
                ->with('author')
                ->latest('report_date')
                ->chunk(500, function ($reports) use ($out): void {
                    foreach ($reports as $report) {
                        fputcsv($out, [
                            $report->report_date->format('Y-m-d'),
                            $report->author->name,
                            $report->body,
                            $report->summary ?? '',
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
