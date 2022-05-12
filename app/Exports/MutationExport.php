<?php

namespace App\Exports;

use App\Models\Mutation;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MutationExport implements ShouldAutoSize, Responsable, FromView, WithStyles
{
    use Exportable;

    private $fileName = 'mutation-report.xls';

    public function __construct(private Request $request)
    {}

    public function view(): View
    {
        $mutations = Mutation::filter($this->request->only('startDate', 'endDate'))->get();
        return view('excel.mutation-report', compact('mutations'));
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestDataRow();
        $lastSecondRow = $lastRow - 1;
        $lastThirdRow = $lastSecondRow - 1;
        $lastFourRow = $lastThirdRow - 1;

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
            ],
            $lastRow => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'left'],
            ],
            $lastSecondRow => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'left'],
            ],
            $lastThirdRow => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'left'],
            ],
            $lastFourRow => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'left'],
            ],
        ];
    }
}
