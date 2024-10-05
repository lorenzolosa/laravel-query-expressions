<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Time;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class LastDayOfMonth implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
    ) {}

    public function getValue(Grammar $grammar)
    {
        $expression = $this->stringize($grammar, $this->expression);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "LAST_DAY({$expression})",
            'pgsql' => "DATE_TRUNC('month', {$expression}) + interval '1 month - 1 day'",
            'sqlite' => "DATE({$expression}, 'start of month', '+1 month', '-1 day')",
            'sqlsrv' => "EOMONTH({$expression})",
        };
    }
}
