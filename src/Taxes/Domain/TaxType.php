<?php

declare(strict_types=1);

namespace App\Taxes\Domain;

enum TaxType: string
{
    case VAT = 'VAT';
    case GST_HST = 'GST/HST';
    case PST = 'PST';
}
