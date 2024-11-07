<?php

declare(strict_types=1);

namespace Taxes\Domain;

enum TaxType: string
{
    case VAT = 'VAT';
    case GST_HST = 'GST/HST';
    case PST = 'PST';
}
