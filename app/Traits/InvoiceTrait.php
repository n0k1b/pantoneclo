<?php
namespace App\Traits;
use App\Traits\UtilitiesTrait;
use Barryvdh\DomPDF\Facade\Pdf;

trait InvoiceTrait{

    use UtilitiesTrait;

    public function getOrderDetailsInvoice($reference_no)
    {
        $order      = $this->getOrderByReference($reference_no);
        $orderArray = $this->getOrderArray($order);
        $pdf        = Pdf::loadView('admin.pdf.invoice', $orderArray);
        return $pdf->download('invoice.pdf');
    }
}
