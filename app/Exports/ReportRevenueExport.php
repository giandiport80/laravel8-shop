<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportRevenueExport implements FromView
{
    private $_results;

    /**
     * Create a new exporter instance.
     *
     * @param array $results query result
     *
     * @return void
     */
    public function __construct($results)
    {
        $this->_results = $results;
    }

    /**
     * Load the view.
     *
     * @return void
     */
    public function view(): View
    {
        return view(
            'admin.reports.exports.revenue_xlsx',
            [
                'revenues' => $this->_results,
            ]
        );
    }
}
