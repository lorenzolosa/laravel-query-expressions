<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Function\Time\LastDayOfMonth;
use Tpetry\QueryExpressions\Value\Value;

it('can compute the last day of month of a column')
    ->expect(new LastDayOfMonth('date'))
    ->toBeExecutable()
    ->toBeMysql('LAST_DAY(`date`)')
    ->toBePgsql('DATE_TRUNC(\'month\', "date") + interval \'1 month - 1 day\'')
    ->toBeSqlite('DATE("date", \'start of month\', \'+1 month\', \'-1 day\')')
    ->toBeSqlsrv('EOMONTH([date])');

it('can compute the last day of month of a date')
    ->expect(new LastDayOfMonth(new Value('2024-01-03')))
    ->toBeExecutable()
    ->toBeMysql("LAST_DAY('2024-01-03')")
    ->toBePgsql("DATE_TRUNC('month', '2024-01-03') + interval '1 month - 1 day'")
    ->toBeSqlite("DATE('2024-01-03', 'start of month', '+1 month', '-1 day')")
    ->toBeSqlsrv('EOMONTH(\'2024-01-03\')');
