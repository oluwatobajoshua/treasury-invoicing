<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FinalInvoice $finalInvoice
 */
$this->assign('title', 'Edit Final Invoice #' . $finalInvoice->invoice_number);

// Reuse the add template
echo $this->element('../FinalInvoices/add');
