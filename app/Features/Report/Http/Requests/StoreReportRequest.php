<?php

declare(strict_types=1);

namespace App\Features\Report\Http\Requests;

use App\Core\Authorization\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::ReportManage->value) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'report_date' => ['required', 'date'],
            'body' => ['required', 'string', 'max:10000'],
        ];
    }
}
