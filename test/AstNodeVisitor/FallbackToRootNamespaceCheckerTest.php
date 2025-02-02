<?php

namespace De\Idrinth\PhpCostEstimator\AstNodeVisitor;

use PHPUnit\Framework\TestCase;

class FallbackToRootNamespaceCheckerTest extends TestCase
{

    public function testAfterTraverse()
    {
        $this->markTestIncomplete();
    }

    public function testEnterNode()
    {
        $this->markTestIncomplete();
        $sut = new FallbackToRootNamespaceChecker();
        $sut->afterTraverse([]);
        $this->assertTrue(true);
    }
}
