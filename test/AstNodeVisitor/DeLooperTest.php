<?php

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\For_;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(DeLooper::class)]
class DeLooperTest extends TestCase
{
    #[Test]
    public function emptyEnlargedStructureMatchesExpectation(): void
    {
        $expectation = '[]';
        $ast = new For_();
        $visitor = new DeLooper();
        self::assertJsonStringEqualsJsonString($expectation, json_encode($visitor->leaveNode($ast)));
    }
    #[Test]
    public function filledEnlargedStructureMatchesExpectation(): void
    {
        $expectation = '[{"nodeType":"Scalar_LNumber","attributes":[],"value":111},{"nodeType":"Scalar_LNumber","attributes":[],"value":111},{"nodeType":"Scalar_LNumber","attributes":[],"value":111},{"nodeType":"Scalar_LNumber","attributes":[],"value":111},{"nodeType":"Scalar_LNumber","attributes":[],"value":111}]';
        $ast = new For_([
            'stmts' => [new LNumber(111)],
        ]);
        $visitor = new DeLooper();
        self::assertJsonStringEqualsJsonString($expectation, json_encode($visitor->leaveNode($ast)));
    }
}
