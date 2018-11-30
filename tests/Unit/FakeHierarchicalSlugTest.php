<?php

namespace Tests\Unit;

use Tests\TestCase;

class FakeHierarchicalSlugTest extends TestCase
{
    /**
     * @test
     * @dataProvider illegalNumberOfLevelsProvider
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_an_exception_if_number_of_levels_is_less_than_one(int $numberOfLevels)
    {
        fake_hierarchical_slug($numberOfLevels);
    }

    public function illegalNumberOfLevelsProvider()
    {
        return [
            [-1],
            [0],
        ];
    }

    /**
     * @test
     * @dataProvider legalNumberOfLevelsProvider
     */
    public function it_generates_hierarchical_slugs_as_expected(int $numberOfLevels)
    {
        $slug = fake_hierarchical_slug($numberOfLevels);
        $this->assertEquals($numberOfLevels - 1, substr_count($slug, '/'));
    }

    public function legalNumberOfLevelsProvider()
    {
        return [
            [1],
            [4],
        ];
    }
}
