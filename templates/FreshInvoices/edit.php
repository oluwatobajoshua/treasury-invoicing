<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FreshInvoice $freshInvoice
 */
$this->assign('title', 'Edit Fresh Invoice #' . $freshInvoice->invoice_number);

// Reuse the add template
echo $this->element('../FreshInvoices/add');
