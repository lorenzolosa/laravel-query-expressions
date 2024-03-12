<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Conditional;

use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;

class Greatest extends ManyArgumentsExpression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar): string
    {
        $expressions = $this->getExpressions($grammar);
        if ($this->identify($grammar) === 'sqlsrv') {
            $expressions = array_map(fn ($expression) => "({$expression})", $expressions);
        }
        $expressionsStr = implode(', ', $expressions);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'pgsql' => "greatest({$expressionsStr})",
            'sqlite' => "max({$expressionsStr})",
            'sqlsrv' => "(select max(n) from (values {$expressionsStr}) as v(n))",
        };
    }
}
