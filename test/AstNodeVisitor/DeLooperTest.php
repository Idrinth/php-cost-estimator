<?php

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\While_;
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
    public function filledEnlargedStructureViForMatchesExpectation(): void
    {
        $expectation = '[{"nodeType":"Scalar_Int","attributes":[],"value":111},{"nodeType":"Scalar_Int","attributes":[],"value":111},{"nodeType":"Scalar_Int","attributes":[],"value":111},{"nodeType":"Scalar_Int","attributes":[],"value":111},{"nodeType":"Scalar_Int","attributes":[],"value":111}]';
        $ast = new For_([
            'stmts' => [new LNumber(111)],
        ]);
        $visitor = new DeLooper();
        self::assertJsonStringEqualsJsonString($expectation, json_encode($visitor->leaveNode($ast)));
    }
    #[Test]
    public function filledEnlargedStructureViaWhileMatchesExpectation(): void
    {
        $expectation = '[{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111}]';
        $ast = new While_(
            new Equal(new DNumber(1), new DNumber(1)),
            [new DNumber(111)]
        );
        $visitor = new DeLooper();
        self::assertJsonStringEqualsJsonString($expectation, json_encode($visitor->leaveNode($ast)));
    }
    #[Test]
    public function filledEnlargedStructureViaDOWhileMatchesExpectation(): void
    {
        $expectation = '[{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111}]';
        $ast = new Do_(
            new Equal(new DNumber(1), new DNumber(1)),
            [new DNumber(111)]
        );
        $visitor = new DeLooper();
        self::assertJsonStringEqualsJsonString($expectation, json_encode($visitor->leaveNode($ast)));
    }
    #[Test]
    public function filledEnlargedStructureViaForeachMatchesExpectation(): void
    {
        $expectation = '[{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111},{"nodeType":"Scalar_Float","attributes":[],"value":111}]';
        $ast = new Foreach_(new Variable('a'), new Variable('b'), ['stmts' => [new DNumber(111)]]);
        $visitor = new DeLooper();
        self::assertJsonStringEqualsJsonString($expectation, json_encode($visitor->leaveNode($ast)));
    }
}
