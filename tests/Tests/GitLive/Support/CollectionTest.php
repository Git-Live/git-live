<?php

/**
 * This file is part of Git-Live
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id\$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 */

namespace Tests\GitLive\Support;

use ArrayAccess;
use ArrayIterator;
use ArrayObject;
use CachingIterator;
use Exception;
use GitLive\Support\Arrayable;
use GitLive\Support\Collection;
use GitLive\Support\Jsonable;
use JsonSerializable;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

/**
 * Class CollectionTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Support
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @internal
 * @coversNothing
 */
class CollectionTest extends TestCase
{
    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFirstReturnsFirstItemInCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->first());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFirstWithCallback()
    {
        $data = new Collection(['foo', 'bar', 'baz']);
        $result = $data->first(static function ($value) {
            return $value === 'bar';
        });
        $this->assertEquals('bar', $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFirstWithCallbackAndDefault()
    {
        $data = new Collection(['foo', 'bar']);
        $result = $data->first(static function ($value) {
            return $value === 'baz';
        }, 'default');
        $this->assertEquals('default', $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFirstWithDefaultAndWithoutCallback()
    {
        $data = new Collection;
        $result = $data->first(null, 'default');
        $this->assertEquals('default', $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFirstWhere()
    {
        $data = new Collection([
            ['material' => 'paper', 'type' => 'book'],
            ['material' => 'rubber', 'type' => 'gasket'],
        ]);
        $this->assertEquals('book', $data->firstWhere('material', 'paper')['type']);
        $this->assertEquals('gasket', $data->firstWhere('material', 'rubber')['type']);
        $this->assertNull($data->firstWhere('material', 'nonexistant'));
        $this->assertNull($data->firstWhere('nonexistant', 'key'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testLastReturnsLastItemInCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('bar', $c->last());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testLastWithCallback()
    {
        $data = new Collection([100, 200, 300]);
        $result = $data->last(static function ($value) {
            return $value < 250;
        });
        $this->assertEquals(200, $result);
        $result = $data->last(static function ($value, $key) {
            return $key < 2;
        });
        $this->assertEquals(200, $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testLastWithCallbackAndDefault()
    {
        $data = new Collection(['foo', 'bar']);
        $result = $data->last(static function ($value) {
            return $value === 'baz';
        }, 'default');
        $this->assertEquals('default', $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testLastWithDefaultAndWithoutCallback()
    {
        $data = new Collection;
        $result = $data->last(null, 'default');
        $this->assertEquals('default', $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPopReturnsAndRemovesLastItemInCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('bar', $c->pop());
        $this->assertEquals('foo', $c->first());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testShiftReturnsAndRemovesFirstItemInCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->shift());
        $this->assertEquals('bar', $c->first());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testEmptyCollectionIsEmpty()
    {
        $c = new Collection;
        $this->assertTrue($c->isEmpty());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testEmptyCollectionIsNotEmpty()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertFalse($c->isEmpty());
        $this->assertTrue($c->isNotEmpty());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCollectionIsConstructed()
    {
        $collection = new Collection('foo');
        $this->assertSame(['foo'], $collection->all());
        $collection = new Collection(2);
        $this->assertSame([2], $collection->all());
        $collection = new Collection(false);
        $this->assertSame([false], $collection->all());
        $collection = new Collection(null);
        $this->assertEmpty($collection->all());
        $collection = new Collection;
        $this->assertEmpty($collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCollectionShuffleWithSeed()
    {
        $collection = new Collection(range(0, 100, 10));
        $firstRandom = $collection->shuffle(1234);
        $secondRandom = $collection->shuffle(1234);
        $this->assertEquals($firstRandom, $secondRandom);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Support\Collection
     */
    public function testGetArrayableItems()
    {
        $collection = new Collection;
        $class = new ReflectionClass($collection);
        $method = $class->getMethod('getArrayableItems');
        $method->setAccessible(true);
        $items = new TestArrayableObject;
        $array = $method->invokeArgs($collection, [$items]);
        $this->assertSame(['foo' => 'bar'], $array);
        $items = new TestJsonableObject;
        $array = $method->invokeArgs($collection, [$items]);
        $this->assertSame(['foo' => 'bar'], $array);
        $items = new TestJsonSerializeObject;
        $array = $method->invokeArgs($collection, [$items]);
        $this->assertSame(['foo' => 'bar'], $array);
        $items = new Collection(['foo' => 'bar']);
        $array = $method->invokeArgs($collection, [$items]);
        $this->assertSame(['foo' => 'bar'], $array);
        $items = ['foo' => 'bar'];
        $array = $method->invokeArgs($collection, [$items]);
        $this->assertSame(['foo' => 'bar'], $array);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testToArrayCallsToArrayOnEachItemInCollection()
    {
        $item1 = m::mock(Arrayable::class);
        $item1->shouldReceive('toArray')->once()->andReturn(['foo.array']);
        $item2 = m::mock(Arrayable::class);
        $item2->shouldReceive('toArray')->once()->andReturn(['bar.array']);
        $c = new Collection([$item1, $item2]);
        $results = $c->toArray();
        $this->assertEquals([['foo.array'], ['bar.array']], $results);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testJsonSerializeCallsToArrayOrJsonSerializeOnEachItemInCollection()
    {
        $item1 = m::mock(JsonSerializable::class);
        $item1->shouldReceive('jsonSerialize')->once()->andReturn(['foo.json']);
        $item2 = m::mock(Arrayable::class);
        $item2->shouldReceive('toArray')->once()->andReturn(['bar.array']);
        $c = new Collection([$item1, $item2]);
        $results = $c->jsonSerialize();
        $this->assertEquals([['foo.json'], ['bar.array']], $results);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testToJsonEncodesTheJsonSerializeResult()
    {
        /**
         * @var self $c
         */
        $c = $this->getMockBuilder(Collection::class)->setMethods(['jsonSerialize'])->getMock();
        $c->expects($this->once())->method('jsonSerialize')->will($this->returnValue(['foo']));
        $results = $c->toJson();
        $this->assertJsonStringEqualsJsonString(json_encode(['foo']), $results);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCastingToStringJsonEncodesTheToArrayResult()
    {
        /**
         * @var self $c
         */
        $c = $this->getMockBuilder(Collection::class)->setMethods(['__toString'])->getMock();
        $c->expects($this->once())->method('__toString')->will($this->returnValue(json_encode(['foo'])));
        $this->assertJsonStringEqualsJsonString(json_encode(['foo']), (string)$c);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testOffsetAccess()
    {
        $c = new Collection(['name' => 'suzunone']);
        $this->assertEquals('suzunone', $c['name']);
        $c['name'] = 'dayle';
        $this->assertEquals('dayle', $c['name']);
        $this->assertTrue(isset($c['name']));
        unset($c['name']);
        $this->assertFalse(isset($c['name']));
        $c[] = 'jason';
        $this->assertEquals('jason', $c[0]);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testArrayAccessOffsetExists()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertTrue($c->offsetExists(0));
        $this->assertTrue($c->offsetExists(1));
        $this->assertFalse($c->offsetExists(1000));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testArrayAccessOffsetGet()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->offsetGet(0));
        $this->assertEquals('bar', $c->offsetGet(1));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testArrayAccessOffsetSet()
    {
        $c = new Collection(['foo', 'foo']);
        $c->offsetSet(1, 'bar');
        $this->assertEquals('bar', $c[1]);
        $c->offsetSet(null, 'qux');
        $this->assertEquals('qux', $c[2]);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testArrayAccessOffsetUnset()
    {
        $c = new Collection(['foo', 'bar']);
        $c->offsetUnset(1);
        $this->assertFalse(isset($c[1]));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testForgetSingleKey()
    {
        $c = new Collection(['foo', 'bar']);
        $c->forget(0);
        $this->assertFalse(isset($c['foo']));
        $c = new Collection(['foo' => 'bar', 'baz' => 'qux']);
        $c->forget('foo');
        $this->assertFalse(isset($c['foo']));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testForgetArrayOfKeys()
    {
        $c = new Collection(['foo', 'bar', 'baz']);
        $c->forget([0, 2]);
        $this->assertFalse(isset($c[0]));
        $this->assertFalse(isset($c[2]));
        $this->assertTrue(isset($c[1]));
        $c = new Collection(['name' => 'suzunone', 'foo' => 'bar', 'baz' => 'qux']);
        $c->forget(['foo', 'baz']);
        $this->assertFalse(isset($c['foo']));
        $this->assertFalse(isset($c['baz']));
        $this->assertTrue(isset($c['name']));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCountable()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertCount(2, $c);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testIterable()
    {
        $c = new Collection(['foo']);
        $this->assertInstanceOf(ArrayIterator::class, $c->getIterator());
        $this->assertEquals(['foo'], $c->getIterator()->getArrayCopy());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCachingIterator()
    {
        $c = new Collection(['foo']);
        $this->assertInstanceOf(CachingIterator::class, $c->getCachingIterator());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFilter()
    {
        $c = new Collection([['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']]);
        $this->assertEquals([1 => ['id' => 2, 'name' => 'World']], $c->filter(static function ($item) {
            return $item['id'] == 2;
        })->all());
        $c = new Collection(['', 'Hello', '', 'World']);
        $this->assertEquals(['Hello', 'World'], $c->filter()->values()->toArray());
        $c = new Collection(['id' => 1, 'first' => 'Hello', 'second' => 'World']);
        $this->assertEquals(['first' => 'Hello', 'second' => 'World'], $c->filter(static function ($item, $key) {
            return $key != 'id';
        })->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderKeyBy()
    {
        $c = new Collection([
            ['id' => 'id1', 'name' => 'first'],
            ['id' => 'id2', 'name' => 'second'],
        ]);
        $this->assertEquals(['id1' => 'first', 'id2' => 'second'], $c->keyBy->id->map->name->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderUnique()
    {
        $c = new Collection([
            ['id' => '1', 'name' => 'first'],
            ['id' => '1', 'name' => 'second'],
        ]);
        $this->assertCount(1, $c->unique->id);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderFilter()
    {
        $c = new Collection([
            new class {
                public $name = 'Alex';

                /**
                 * @covers \GitLive\Support\Collection
                 */
                public function active()
                {
                    return true;
                }
            },
            new class {
                public $name = 'John';

                /**
                 * @covers \GitLive\Support\Collection
                 */
                public function active()
                {
                    return false;
                }
            },
        ]);
        $this->assertCount(1, $c->filter->active());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhere()
    {
        $c = new Collection([['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]]);
        $this->assertEquals(
            [['v' => 3], ['v' => '3']],
            $c->where('v', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 3], ['v' => '3']],
            $c->where('v', '=', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 3], ['v' => '3']],
            $c->where('v', '==', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 3], ['v' => '3']],
            $c->where('v', 'garbage', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 3]],
            $c->where('v', '===', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => 4]],
            $c->where('v', '<>', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => 4]],
            $c->where('v', '!=', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => '3'], ['v' => 4]],
            $c->where('v', '!==', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3']],
            $c->where('v', '<=', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 3], ['v' => '3'], ['v' => 4]],
            $c->where('v', '>=', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2]],
            $c->where('v', '<', 3)->values()->all()
        );
        $this->assertEquals(
            [['v' => 4]],
            $c->where('v', '>', 3)->values()->all()
        );
        $object = (object)['foo' => 'bar'];
        $this->assertEquals(
            [],
            $c->where('v', $object)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]],
            $c->where('v', '<>', $object)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]],
            $c->where('v', '!=', $object)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]],
            $c->where('v', '!==', $object)->values()->all()
        );
        $this->assertEquals(
            [],
            $c->where('v', '>', $object)->values()->all()
        );
        $c = new Collection([['v' => 1], ['v' => $object]]);
        $this->assertEquals(
            [['v' => $object]],
            $c->where('v', $object)->values()->all()
        );
        $this->assertEquals(
            [['v' => 1], ['v' => $object]],
            $c->where('v', '<>', null)->values()->all()
        );
        $this->assertEquals(
            [],
            $c->where('v', '<', null)->values()->all()
        );
        /*
        $c = new Collection([['v' => 1], ['v' => new HtmlString('hello')]]);
        $this->assertEquals(
            [['v' => new HtmlString('hello')]],
            $c->where('v', 'hello')->values()->all()
        );
        $c = new Collection([['v' => 1], ['v' => 'hello']]);
        $this->assertEquals(
            [['v' => 'hello']],
            $c->where('v', new HtmlString('hello'))->values()->all()
        );
        */
        $c = new Collection([['v' => 1], ['v' => 2], ['v' => null]]);
        $this->assertEquals(
            [['v' => 1], ['v' => 2]],
            $c->where('v')->values()->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhereStrict()
    {
        $c = new Collection([['v' => 3], ['v' => '3']]);
        $this->assertEquals(
            [['v' => 3]],
            $c->whereStrict('v', 3)->values()->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhereInstanceOf()
    {
        $c = new Collection([new stdClass, new stdClass, new Collection, new stdClass]);
        $this->assertCount(3, $c->whereInstanceOf(stdClass::class));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhereIn()
    {
        $c = new Collection([['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]]);
        $this->assertEquals([['v' => 1], ['v' => 3], ['v' => '3']], $c->whereIn('v', [1, 3])->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhereInStrict()
    {
        $c = new Collection([['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]]);
        $this->assertEquals([['v' => 1], ['v' => 3]], $c->whereInStrict('v', [1, 3])->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhereNotIn()
    {
        $c = new Collection([['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]]);
        $this->assertEquals([['v' => 2], ['v' => 4]], $c->whereNotIn('v', [1, 3])->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhereNotInStrict()
    {
        $c = new Collection([['v' => 1], ['v' => 2], ['v' => 3], ['v' => '3'], ['v' => 4]]);
        $this->assertEquals([['v' => 2], ['v' => '3'], ['v' => 4]], $c->whereNotInStrict('v', [1, 3])->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testValues()
    {
        $c = new Collection([['id' => 1, 'name' => 'Hello'], ['id' => 2, 'name' => 'World']]);
        $this->assertEquals([['id' => 2, 'name' => 'World']], $c->filter(static function ($item) {
            return $item['id'] == 2;
        })->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFlatten()
    {
        // Flat arrays are unaffected
        $c = new Collection(['#foo', '#bar', '#baz']);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Nested arrays are flattened with existing flat items
        $c = new Collection([['#foo', '#bar'], '#baz']);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Sets of nested arrays are flattened
        $c = new Collection([['#foo', '#bar'], ['#baz']]);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Deeply nested arrays are flattened
        $c = new Collection([['#foo', ['#bar']], ['#baz']]);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Nested collections are flattened alongside arrays
        $c = new Collection([new Collection(['#foo', '#bar']), ['#baz']]);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Nested collections containing plain arrays are flattened
        $c = new Collection([new Collection(['#foo', ['#bar']]), ['#baz']]);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Nested arrays containing collections are flattened
        $c = new Collection([['#foo', new Collection(['#bar'])], ['#baz']]);
        $this->assertEquals(['#foo', '#bar', '#baz'], $c->flatten()->all());
        // Nested arrays containing collections containing arrays are flattened
        $c = new Collection([['#foo', new Collection(['#bar', ['#zap']])], ['#baz']]);
        $this->assertEquals(['#foo', '#bar', '#zap', '#baz'], $c->flatten()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFlattenWithDepth()
    {
        // No depth flattens recursively
        $c = new Collection([['#foo', ['#bar', ['#baz']]], '#zap']);
        $this->assertEquals(['#foo', '#bar', '#baz', '#zap'], $c->flatten()->all());
        // Specifying a depth only flattens to that depth
        $c = new Collection([['#foo', ['#bar', ['#baz']]], '#zap']);
        $this->assertEquals(['#foo', ['#bar', ['#baz']], '#zap'], $c->flatten(1)->all());
        $c = new Collection([['#foo', ['#bar', ['#baz']]], '#zap']);
        $this->assertEquals(['#foo', '#bar', ['#baz'], '#zap'], $c->flatten(2)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFlattenIgnoresKeys()
    {
        // No depth ignores keys
        $c = new Collection(['#foo', ['key' => '#bar'], ['key' => '#baz'], 'key' => '#zap']);
        $this->assertEquals(['#foo', '#bar', '#baz', '#zap'], $c->flatten()->all());
        // Depth of 1 ignores keys
        $c = new Collection(['#foo', ['key' => '#bar'], ['key' => '#baz'], 'key' => '#zap']);
        $this->assertEquals(['#foo', '#bar', '#baz', '#zap'], $c->flatten(1)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMergeNull()
    {
        $c = new Collection(['name' => 'Hello']);
        $this->assertEquals(['name' => 'Hello'], $c->merge(null)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMergeArray()
    {
        $c = new Collection(['name' => 'Hello']);
        $this->assertEquals(['name' => 'Hello', 'id' => 1], $c->merge(['id' => 1])->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMergeCollection()
    {
        $c = new Collection(['name' => 'Hello']);
        $this->assertEquals(['name' => 'World', 'id' => 1], $c->merge(new Collection(['name' => 'World', 'id' => 1]))->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnionNull()
    {
        $c = new Collection(['name' => 'Hello']);
        $this->assertEquals(['name' => 'Hello'], $c->union(null)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnionArray()
    {
        $c = new Collection(['name' => 'Hello']);
        $this->assertEquals(['name' => 'Hello', 'id' => 1], $c->union(['id' => 1])->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnionCollection()
    {
        $c = new Collection(['name' => 'Hello']);
        $this->assertEquals(['name' => 'Hello', 'id' => 1], $c->union(new Collection(['name' => 'World', 'id' => 1]))->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffCollection()
    {
        $c = new Collection(['id' => 1, 'first_word' => 'Hello']);
        $this->assertEquals(['id' => 1], $c->diff(new Collection(['first_word' => 'Hello', 'last_word' => 'World']))->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffUsingWithCollection()
    {
        $c = new Collection(['en_GB', 'fr', 'HR']);
        // demonstrate that diffKeys wont support case insensitivity
        $this->assertEquals(['en_GB', 'fr', 'HR'], $c->diff(new Collection(['en_gb', 'hr']))->values()->toArray());
        // allow for case insensitive difference
        $this->assertEquals(['fr'], $c->diffUsing(new Collection(['en_gb', 'hr']), 'strcasecmp')->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffUsingWithNull()
    {
        $c = new Collection(['en_GB', 'fr', 'HR']);
        $this->assertEquals(['en_GB', 'fr', 'HR'], $c->diffUsing(null, 'strcasecmp')->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffNull()
    {
        $c = new Collection(['id' => 1, 'first_word' => 'Hello']);
        $this->assertEquals(['id' => 1, 'first_word' => 'Hello'], $c->diff(null)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffKeys()
    {
        $c1 = new Collection(['id' => 1, 'first_word' => 'Hello']);
        $c2 = new Collection(['id' => 123, 'foo_bar' => 'Hello']);
        $this->assertEquals(['first_word' => 'Hello'], $c1->diffKeys($c2)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffKeysUsing()
    {
        $c1 = new Collection(['id' => 1, 'first_word' => 'Hello']);
        $c2 = new Collection(['ID' => 123, 'foo_bar' => 'Hello']);
        // demonstrate that diffKeys wont support case insensitivity
        $this->assertEquals(['id' => 1, 'first_word' => 'Hello'], $c1->diffKeys($c2)->all());
        // allow for case insensitive difference
        $this->assertEquals(['first_word' => 'Hello'], $c1->diffKeysUsing($c2, 'strcasecmp')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffAssoc()
    {
        $c1 = new Collection(['id' => 1, 'first_word' => 'Hello', 'not_affected' => 'value']);
        $c2 = new Collection(['id' => 123, 'foo_bar' => 'Hello', 'not_affected' => 'value']);
        $this->assertEquals(['id' => 1, 'first_word' => 'Hello'], $c1->diffAssoc($c2)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testDiffAssocUsing()
    {
        $c1 = new Collection(['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red']);
        $c2 = new Collection(['A' => 'green', 'yellow', 'red']);
        // demonstrate that the case of the keys will affect the output when diffAssoc is used
        $this->assertEquals(['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'], $c1->diffAssoc($c2)->all());
        // allow for case insensitive difference
        $this->assertEquals(['b' => 'brown', 'c' => 'blue', 'red'], $c1->diffAssocUsing($c2, 'strcasecmp')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testEach()
    {
        $c = new Collection($original = [1, 2, 'foo' => 'bar', 'bam' => 'baz']);
        $result = [];
        $c->each(static function ($item, $key) use (&$result) {
            $result[$key] = $item;
        });
        $this->assertEquals($original, $result);
        $result = [];
        $c->each(static function ($item, $key) use (&$result) {
            $result[$key] = $item;
            if (is_string($key)) {
                return false;
            }

            return null;
        });
        $this->assertEquals([1, 2, 'foo' => 'bar'], $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testEachSpread()
    {
        $c = new Collection([[1, 'a'], [2, 'b']]);
        $result = [];
        $c->eachSpread(static function ($number, $character) use (&$result) {
            $result[] = [$number, $character];
        });
        $this->assertEquals($c->all(), $result);
        $result = [];
        $c->eachSpread(static function ($number, $character) use (&$result) {
            $result[] = [$number, $character];

            return false;
        });
        $this->assertEquals([[1, 'a']], $result);
        $result = [];
        $c->eachSpread(static function ($number, $character, $key) use (&$result) {
            $result[] = [$number, $character, $key];
        });
        $this->assertEquals([[1, 'a', 0], [2, 'b', 1]], $result);
        $c = new Collection([new Collection([1, 'a']), new Collection([2, 'b'])]);
        $result = [];
        $c->eachSpread(static function ($number, $character, $key) use (&$result) {
            $result[] = [$number, $character, $key];
        });
        $this->assertEquals([[1, 'a', 0], [2, 'b', 1]], $result);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testIntersectNull()
    {
        $c = new Collection(['id' => 1, 'first_word' => 'Hello']);
        $this->assertEquals([], $c->intersect(null)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testIntersectCollection()
    {
        $c = new Collection(['id' => 1, 'first_word' => 'Hello']);
        $this->assertEquals(['first_word' => 'Hello'], $c->intersect(new Collection(['first_world' => 'Hello', 'last_word' => 'World']))->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testIntersectByKeysNull()
    {
        $c = new Collection(['name' => 'Mateus', 'age' => 18]);
        $this->assertEquals([], $c->intersectByKeys(null)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testIntersectByKeys()
    {
        $c = new Collection(['name' => 'Mateus', 'age' => 18]);
        $this->assertEquals(['name' => 'Mateus'], $c->intersectByKeys(new Collection(['name' => 'Mateus', 'surname' => 'Guimaraes']))->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnique()
    {
        $c = new Collection(['Hello', 'World', 'World']);
        $this->assertEquals(['Hello', 'World'], $c->unique()->all());
        $c = new Collection([[1, 2], [1, 2], [2, 3], [3, 4], [2, 3]]);
        $this->assertEquals([[1, 2], [2, 3], [3, 4]], $c->unique()->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUniqueWithCallback()
    {
        $c = new Collection([
            1 => ['id' => 1, 'first' => 'suzunone', 'last' => 'Otwell'],
            2 => ['id' => 2, 'first' => 'suzunone', 'last' => 'Otwell'],
            3 => ['id' => 3, 'first' => 'Abigail', 'last' => 'Otwell'],
            4 => ['id' => 4, 'first' => 'Abigail', 'last' => 'Otwell'],
            5 => ['id' => 5, 'first' => 'suzunone', 'last' => 'Swift'],
            6 => ['id' => 6, 'first' => 'suzunone', 'last' => 'Swift'],
        ]);
        $this->assertEquals([
            1 => ['id' => 1, 'first' => 'suzunone', 'last' => 'Otwell'],
            3 => ['id' => 3, 'first' => 'Abigail', 'last' => 'Otwell'],
        ], $c->unique('first')->all());
        $this->assertEquals([
            1 => ['id' => 1, 'first' => 'suzunone', 'last' => 'Otwell'],
            3 => ['id' => 3, 'first' => 'Abigail', 'last' => 'Otwell'],
            5 => ['id' => 5, 'first' => 'suzunone', 'last' => 'Swift'],
        ], $c->unique(static function ($item) {
            return $item['first'] . $item['last'];
        })->all());
        $this->assertEquals([
            1 => ['id' => 1, 'first' => 'suzunone', 'last' => 'Otwell'],
            2 => ['id' => 2, 'first' => 'suzunone', 'last' => 'Otwell'],
        ], $c->unique(static function ($item, $key) {
            return $key % 2;
        })->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUniqueStrict()
    {
        $c = new Collection([
            [
                'id' => '0',
                'name' => 'zero',
            ],
            [
                'id' => '00',
                'name' => 'double zero',
            ],
            [
                'id' => '0',
                'name' => 'again zero',
            ],
        ]);
        $this->assertEquals([
            [
                'id' => '0',
                'name' => 'zero',
            ],
            [
                'id' => '00',
                'name' => 'double zero',
            ],
        ], $c->uniqueStrict('id')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCollapse()
    {
        $data = new Collection([[$object1 = new stdClass], [$object2 = new stdClass]]);
        $this->assertEquals([$object1, $object2], $data->collapse()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCollapseWithNestedCollections()
    {
        $data = new Collection([new Collection([1, 2, 3]), new Collection([4, 5, 6])]);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $data->collapse()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCrossJoin()
    {
        // Cross join with an array
        $this->assertEquals(
            [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']],
            (new Collection([1, 2]))->crossJoin(['a', 'b'])->all()
        );
        // Cross join with a collection
        $this->assertEquals(
            [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']],
            (new Collection([1, 2]))->crossJoin(new Collection(['a', 'b']))->all()
        );
        // Cross join with 2 collections
        $this->assertEquals(
            [
                [1, 'a', 'I'], [1, 'a', 'II'],
                [1, 'b', 'I'], [1, 'b', 'II'],
                [2, 'a', 'I'], [2, 'a', 'II'],
                [2, 'b', 'I'], [2, 'b', 'II'],
            ],
            (new Collection([1, 2]))->crossJoin(
                new Collection(['a', 'b']),
                new Collection(['I', 'II'])
            )->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSort()
    {
        $data = (new Collection([5, 3, 1, 2, 4]))->sort();
        $this->assertEquals([1, 2, 3, 4, 5], $data->values()->all());
        $data = (new Collection([-1, -3, -2, -4, -5, 0, 5, 3, 1, 2, 4]))->sort();
        $this->assertEquals([-5, -4, -3, -2, -1, 0, 1, 2, 3, 4, 5], $data->values()->all());
        $data = (new Collection(['foo', 'bar-10', 'bar-1']))->sort();
        $this->assertEquals(['bar-1', 'bar-10', 'foo'], $data->values()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSortWithCallback()
    {
        $data = (new Collection([5, 3, 1, 2, 4]))->sort(static function ($a, $b) {
            if ($a === $b) {
                return 0;
            }

            return ($a < $b) ? -1 : 1;
        });
        $this->assertEquals(range(1, 5), array_values($data->all()));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSortBy()
    {
        $data = new Collection(['suzunone', 'dayle']);
        $data = $data->sortBy(static function ($x) {
            return $x;
        });
        $this->assertEquals(['dayle', 'suzunone'], array_values($data->all()));
        $data = new Collection(['dayle', 'suzunone']);
        $data = $data->sortByDesc(static function ($x) {
            return $x;
        });
        $this->assertEquals(['suzunone', 'dayle'], array_values($data->all()));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSortByString()
    {
        $data = new Collection([['name' => 'suzunone'], ['name' => 'dayle']]);
        $data = $data->sortBy('name', SORT_STRING);
        $this->assertEquals([['name' => 'dayle'], ['name' => 'suzunone']], array_values($data->all()));
        $data = new Collection([['name' => 'suzunone'], ['name' => 'dayle']]);
        $data = $data->sortBy('name', SORT_STRING);
        $this->assertEquals([['name' => 'dayle'], ['name' => 'suzunone']], array_values($data->all()));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSortByAlwaysReturnsAssoc()
    {
        $data = new Collection(['a' => 'suzunone', 'b' => 'dayle']);
        $data = $data->sortBy(static function ($x) {
            return $x;
        });
        $this->assertEquals(['b' => 'dayle', 'a' => 'suzunone'], $data->all());
        $data = new Collection(['suzunone', 'dayle']);
        $data = $data->sortBy(static function ($x) {
            return $x;
        });
        $this->assertEquals([1 => 'dayle', 0 => 'suzunone'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSortKeys()
    {
        $data = new Collection(['b' => 'dayle', 'a' => 'suzunone']);
        $this->assertEquals(['a' => 'suzunone', 'b' => 'dayle'], $data->sortKeys()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSortKeysDesc()
    {
        $data = new Collection(['a' => 'suzunone', 'b' => 'dayle']);
        $this->assertEquals(['b' => 'dayle', 'a' => 'suzunone'], $data->sortKeys()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testReverse()
    {
        $data = new Collection(['zaeed', 'alan']);
        $reversed = $data->reverse();
        $this->assertSame([1 => 'alan', 0 => 'zaeed'], $reversed->all());
        $data = new Collection(['name' => 'suzunone', 'tool' => 'gitlive']);
        $reversed = $data->reverse();
        $this->assertSame(['tool' => 'gitlive', 'name' => 'suzunone'], $reversed->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFlip()
    {
        $data = new Collection(['name' => 'suzunone', 'tool' => 'gitlive']);
        $this->assertEquals(['suzunone' => 'name', 'gitlive' => 'tool'], $data->flip()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testChunk()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $data = $data->chunk(3);
        $this->assertInstanceOf(Collection::class, $data);
        $this->assertInstanceOf(Collection::class, $data[0]);
        $this->assertCount(4, $data);
        $this->assertEquals([1, 2, 3], $data[0]->toArray());
        $this->assertEquals([9 => 10], $data[3]->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testChunkWhenGivenZeroAsSize()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $this->assertEquals(
            [],
            $collection->chunk(0)->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testChunkWhenGivenLessThanZero()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $this->assertEquals(
            [],
            $collection->chunk(-1)->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testEvery()
    {
        $c = new Collection([]);
        $this->assertTrue($c->every('key', 'value'));
        $this->assertTrue($c->every(static function () {
            return false;
        }));
        $c = new Collection([['age' => 18], ['age' => 20], ['age' => 20]]);
        $this->assertFalse($c->every('age', 18));
        $this->assertTrue($c->every('age', '>=', 18));
        $this->assertTrue($c->every(static function ($item) {
            return $item['age'] >= 18;
        }));
        $this->assertFalse($c->every(static function ($item) {
            return $item['age'] >= 20;
        }));
        $c = new Collection([null, null]);
        $this->assertTrue($c->every(static function ($item) {
            return $item === null;
        }));
        $c = new Collection([['active' => true], ['active' => true]]);
        $this->assertTrue($c->every('active'));
        $this->assertTrue($c->every->active);
        $this->assertFalse($c->push(['active' => false])->every->active);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testExcept()
    {
        $data = new Collection(['first' => 'suzunone', 'last' => 'Otwell', 'email' => 'suzunoneotwell@gmail.com']);
        $this->assertEquals(['first' => 'suzunone'], $data->except(['last', 'email', 'missing'])->all());
        $this->assertEquals(['first' => 'suzunone'], $data->except('last', 'email', 'missing')->all());
        $this->assertEquals(['first' => 'suzunone'], $data->except(collect(['last', 'email', 'missing']))->all());
        $this->assertEquals(['first' => 'suzunone', 'email' => 'suzunoneotwell@gmail.com'], $data->except(['last'])->all());
        $this->assertEquals(['first' => 'suzunone', 'email' => 'suzunoneotwell@gmail.com'], $data->except('last')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testExceptSelf()
    {
        $data = new Collection(['first' => 'suzunone', 'last' => 'Otwell']);
        $this->assertEquals(['first' => 'suzunone', 'last' => 'Otwell'], $data->except($data)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPluckWithArrayAndObjectValues()
    {
        $data = new Collection([(object)['name' => 'suzunone', 'email' => 'foo'], ['name' => 'dayle', 'email' => 'bar']]);
        $this->assertEquals(['suzunone' => 'foo', 'dayle' => 'bar'], $data->pluck('email', 'name')->all());
        $this->assertEquals(['foo', 'bar'], $data->pluck('email')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPluckWithArrayAccessValues()
    {
        $data = new Collection([
            new TestArrayAccessImplementation(['name' => 'suzunone', 'email' => 'foo']),
            new TestArrayAccessImplementation(['name' => 'dayle', 'email' => 'bar']),
        ]);
        $this->assertEquals(['suzunone' => 'foo', 'dayle' => 'bar'], $data->pluck('email', 'name')->all());
        $this->assertEquals(['foo', 'bar'], $data->pluck('email')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHas()
    {
        $data = new Collection(['id' => 1, 'first' => 'Hello', 'second' => 'World']);
        $this->assertTrue($data->has('first'));
        $this->assertFalse($data->has('third'));
        $this->assertTrue($data->has(['first', 'second']));
        $this->assertFalse($data->has(['third', 'first']));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testImplode()
    {
        $data = new Collection([['name' => 'suzunone', 'email' => 'foo'], ['name' => 'dayle', 'email' => 'bar']]);
        $this->assertEquals('foobar', $data->implode('email'));
        $this->assertEquals('foo,bar', $data->implode('email', ','));
        $data = new Collection(['suzunone', 'dayle']);
        $this->assertEquals('suzunonedayle', $data->implode(''));
        $this->assertEquals('suzunone,dayle', $data->implode(','));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testTake()
    {
        $data = new Collection(['suzunone', 'dayle', 'shawn']);
        $data = $data->take(2);
        $this->assertEquals(['suzunone', 'dayle'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPut()
    {
        $data = new Collection(['name' => 'suzunone', 'email' => 'foo']);
        $data = $data->put('name', 'dayle');
        $this->assertEquals(['name' => 'dayle', 'email' => 'foo'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPutWithNoKey()
    {
        $data = new Collection(['suzunone', 'shawn']);
        $data = $data->put(null, 'dayle');
        $this->assertEquals(['suzunone', 'shawn', 'dayle'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testRandom()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $random = $data->random();
        $this->assertInternalType('integer', $random);
        $this->assertContains($random, $data->all());
        $random = $data->random(0);
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(0, $random);
        $random = $data->random(1);
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(1, $random);
        $random = $data->random(2);
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(2, $random);
        $random = $data->random('0');
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(0, $random);
        $random = $data->random('1');
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(1, $random);
        $random = $data->random('2');
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(2, $random);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testRandomOnEmptyCollection()
    {
        $data = new Collection;
        $random = $data->random(0);
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(0, $random);
        $random = $data->random('0');
        $this->assertInstanceOf(Collection::class, $random);
        $this->assertCount(0, $random);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testTakeLast()
    {
        $data = new Collection(['suzunone', 'dayle', 'shawn']);
        $data = $data->take(-2);
        $this->assertEquals([1 => 'dayle', 2 => 'shawn'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMakeMethod()
    {
        $collection = Collection::make('foo');
        $this->assertEquals(['foo'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMakeMethodFromNull()
    {
        $collection = Collection::make(null);
        $this->assertEquals([], $collection->all());
        $collection = Collection::make();
        $this->assertEquals([], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMakeMethodFromCollection()
    {
        $firstCollection = Collection::make(['foo' => 'bar']);
        $secondCollection = Collection::make($firstCollection);
        $this->assertEquals(['foo' => 'bar'], $secondCollection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMakeMethodFromArray()
    {
        $collection = Collection::make(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithScalar()
    {
        $collection = Collection::wrap('foo');
        $this->assertEquals(['foo'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithArray()
    {
        $collection = Collection::wrap(['foo']);
        $this->assertEquals(['foo'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithArrayable()
    {
        $collection = Collection::wrap($o = new TestArrayableObject);
        $this->assertEquals([$o], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithJsonable()
    {
        $collection = Collection::wrap($o = new TestJsonableObject);
        $this->assertEquals([$o], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithJsonSerialize()
    {
        $collection = Collection::wrap($o = new TestJsonSerializeObject);
        $this->assertEquals([$o], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithCollectionClass()
    {
        $collection = Collection::wrap(Collection::make(['foo']));
        $this->assertEquals(['foo'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWrapWithCollectionSubclass()
    {
        $collection = TestCollectionSubclass::wrap(Collection::make(['foo']));
        $this->assertEquals(['foo'], $collection->all());
        $this->assertInstanceOf(TestCollectionSubclass::class, $collection);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnwrapCollection()
    {
        $collection = new Collection(['foo']);
        $this->assertEquals(['foo'], Collection::unwrap($collection));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnwrapCollectionWithArray()
    {
        $this->assertEquals(['foo'], Collection::unwrap(['foo']));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnwrapCollectionWithScalar()
    {
        /** @noinspection PhpParamsInspection */
        $this->assertEquals('foo', Collection::unwrap('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testTimesMethod()
    {
        $two = Collection::times(2, static function ($number) {
            return 'slug-' . $number;
        });
        $zero = Collection::times(0, static function ($number) {
            return 'slug-' . $number;
        });
        $negative = Collection::times(-4, static function ($number) {
            return 'slug-' . $number;
        });
        $range = Collection::times(5);
        $this->assertEquals(['slug-1', 'slug-2'], $two->all());
        $this->assertTrue($zero->isEmpty());
        $this->assertTrue($negative->isEmpty());
        $this->assertEquals(range(1, 5), $range->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConstructMakeFromObject()
    {
        $object = new stdClass;
        $object->foo = 'bar';
        $collection = Collection::make($object);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConstructMethod()
    {
        $collection = new Collection('foo');
        $this->assertEquals(['foo'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConstructMethodFromNull()
    {
        $collection = new Collection(null);
        $this->assertEquals([], $collection->all());
        $collection = new Collection;
        $this->assertEquals([], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConstructMethodFromCollection()
    {
        $firstCollection = new Collection(['foo' => 'bar']);
        $secondCollection = new Collection($firstCollection);
        $this->assertEquals(['foo' => 'bar'], $secondCollection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConstructMethodFromArray()
    {
        $collection = new Collection(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConstructMethodFromObject()
    {
        $object = new stdClass;
        $object->foo = 'bar';
        $collection = new Collection($object);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplice()
    {
        $data = new Collection(['foo', 'baz']);
        $data->splice(1);
        $this->assertEquals(['foo'], $data->all());
        $data = new Collection(['foo', 'baz']);
        $data->splice(1, 0, 'bar');
        $this->assertEquals(['foo', 'bar', 'baz'], $data->all());
        $data = new Collection(['foo', 'baz']);
        $data->splice(1, 1);
        $this->assertEquals(['foo'], $data->all());
        $data = new Collection(['foo', 'baz']);
        $cut = $data->splice(1, 1, 'bar');
        $this->assertEquals(['foo', 'bar'], $data->all());
        $this->assertEquals(['baz'], $cut->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGetPluckValueWithAccessors()
    {
        $model = new TestAccessorEloquentTestStub(['some' => 'foo']);
        $modelTwo = new TestAccessorEloquentTestStub(['some' => 'bar']);
        $data = new Collection([$model, $modelTwo]);
        $this->assertEquals(['foo', 'bar'], $data->pluck('some')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMap()
    {
        $data = new Collection(['first' => 'suzunone', 'last' => 'eleven']);
        $data = $data->map(static function ($item, $key) {
            return $key . '-' . strrev($item);
        });
        $this->assertEquals(['first' => 'first-enonuzus', 'last' => 'last-nevele'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapSpread()
    {
        $c = new Collection([[1, 'a'], [2, 'b']]);
        $result = $c->mapSpread(static function ($number, $character) {
            return "{$number}-{$character}";
        });
        $this->assertEquals(['1-a', '2-b'], $result->all());
        $result = $c->mapSpread(static function ($number, $character, $key) {
            return "{$number}-{$character}-{$key}";
        });
        $this->assertEquals(['1-a-0', '2-b-1'], $result->all());
        $c = new Collection([new Collection([1, 'a']), new Collection([2, 'b'])]);
        $result = $c->mapSpread(static function ($number, $character, $key) {
            return "{$number}-{$character}-{$key}";
        });
        $this->assertEquals(['1-a-0', '2-b-1'], $result->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testFlatMap()
    {
        $data = new Collection([
            ['name' => 'suzunone', 'hobbies' => ['programming', 'basketball']],
            ['name' => 'adam', 'hobbies' => ['music', 'powerlifting']],
        ]);
        $data = $data->flatMap(static function ($person) {
            return $person['hobbies'];
        });
        $this->assertEquals(['programming', 'basketball', 'music', 'powerlifting'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapToDictionary()
    {
        $data = new Collection([
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 3, 'name' => 'C'],
            ['id' => 4, 'name' => 'B'],
        ]);
        $groups = $data->mapToDictionary(static function ($item, $key) {
            return [$item['name'] => $item['id']];
        });
        $this->assertInstanceOf(Collection::class, $groups);
        $this->assertEquals(['A' => [1], 'B' => [2, 4], 'C' => [3]], $groups->toArray());
        $this->assertInternalType('array', $groups['A']);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapToDictionaryWithNumericKeys()
    {
        $data = new Collection([1, 2, 3, 2, 1]);
        $groups = $data->mapToDictionary(static function ($item, $key) {
            return [$item => $key];
        });
        $this->assertEquals([1 => [0, 4], 2 => [1, 3], 3 => [2]], $groups->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapToGroups()
    {
        $data = new Collection([
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 3, 'name' => 'C'],
            ['id' => 4, 'name' => 'B'],
        ]);
        $groups = $data->mapToGroups(static function ($item, $key) {
            return [$item['name'] => $item['id']];
        });
        $this->assertInstanceOf(Collection::class, $groups);
        $this->assertEquals(['A' => [1], 'B' => [2, 4], 'C' => [3]], $groups->toArray());
        $this->assertInstanceOf(Collection::class, $groups['A']);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapToGroupsWithNumericKeys()
    {
        $data = new Collection([1, 2, 3, 2, 1]);
        $groups = $data->mapToGroups(static function ($item, $key) {
            return [$item => $key];
        });
        $this->assertEquals([1 => [0, 4], 2 => [1, 3], 3 => [2]], $groups->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapWithKeys()
    {
        $data = new Collection([
            ['name' => 'Blastoise', 'type' => 'Water', 'idx' => 9],
            ['name' => 'Charmander', 'type' => 'Fire', 'idx' => 4],
            ['name' => 'Dragonair', 'type' => 'Dragon', 'idx' => 148],
        ]);
        $data = $data->mapWithKeys(static function ($pokemon) {
            return [$pokemon['name'] => $pokemon['type']];
        });
        $this->assertEquals(
            ['Blastoise' => 'Water', 'Charmander' => 'Fire', 'Dragonair' => 'Dragon'],
            $data->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapWithKeysIntegerKeys()
    {
        $data = new Collection([
            ['id' => 1, 'name' => 'A'],
            ['id' => 3, 'name' => 'B'],
            ['id' => 2, 'name' => 'C'],
        ]);
        $data = $data->mapWithKeys(static function ($item) {
            return [$item['id'] => $item];
        });
        $this->assertSame(
            [1, 3, 2],
            $data->keys()->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapWithKeysMultipleRows()
    {
        $data = new Collection([
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 3, 'name' => 'C'],
        ]);
        $data = $data->mapWithKeys(static function ($item) {
            return [$item['id'] => $item['name'], $item['name'] => $item['id']];
        });
        $this->assertSame(
            [
                1 => 'A',
                'A' => 1,
                2 => 'B',
                'B' => 2,
                3 => 'C',
                'C' => 3,
            ],
            $data->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapWithKeysCallbackKey()
    {
        $data = new Collection([
            3 => ['id' => 1, 'name' => 'A'],
            5 => ['id' => 3, 'name' => 'B'],
            4 => ['id' => 2, 'name' => 'C'],
        ]);
        $data = $data->mapWithKeys(static function ($item, $key) {
            return [$key => $item['id']];
        });
        $this->assertSame(
            [3, 5, 4],
            $data->keys()->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapInto()
    {
        $data = new Collection([
            'first', 'second',
        ]);
        $data = $data->mapInto(TestCollectionMapIntoObject::class);
        $this->assertEquals('first', $data[0]->value);
        $this->assertEquals('second', $data[1]->value);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testNth()
    {
        $data = new Collection([
            6 => 'a',
            4 => 'b',
            7 => 'c',
            1 => 'd',
            5 => 'e',
            3 => 'f',
        ]);
        $this->assertEquals(['a', 'e'], $data->nth(4)->all());
        $this->assertEquals(['b', 'f'], $data->nth(4, 1)->all());
        $this->assertEquals(['c'], $data->nth(4, 2)->all());
        $this->assertEquals(['d'], $data->nth(4, 3)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMapWithKeysOverwritingKeys()
    {
        $data = new Collection([
            ['id' => 1, 'name' => 'A'],
            ['id' => 2, 'name' => 'B'],
            ['id' => 1, 'name' => 'C'],
        ]);
        $data = $data->mapWithKeys(static function ($item) {
            return [$item['id'] => $item['name']];
        });
        $this->assertSame(
            [
                1 => 'C',
                2 => 'B',
            ],
            $data->all()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testTransform()
    {
        $data = new Collection(['first' => 'suzunone', 'last' => 'eleven']);
        $data->transform(static function ($item, $key) {
            return $key . '-' . strrev($item);
        });
        $this->assertEquals(['first' => 'first-enonuzus', 'last' => 'last-nevele'], $data->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByAttribute()
    {
        $data = new Collection([['rating' => 1, 'url' => '1'], ['rating' => 1, 'url' => '1'], ['rating' => 2, 'url' => '2']]);
        $result = $data->groupBy('rating');
        $this->assertEquals([1 => [['rating' => 1, 'url' => '1'], ['rating' => 1, 'url' => '1']], 2 => [['rating' => 2, 'url' => '2']]], $result->toArray());
        $result = $data->groupBy('url');
        $this->assertEquals([1 => [['rating' => 1, 'url' => '1'], ['rating' => 1, 'url' => '1']], 2 => [['rating' => 2, 'url' => '2']]], $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByAttributePreservingKeys()
    {
        $data = new Collection([10 => ['rating' => 1, 'url' => '1'], 20 => ['rating' => 1, 'url' => '1'], 30 => ['rating' => 2, 'url' => '2']]);
        $result = $data->groupBy('rating', true);
        $expected_result = [
            1 => [10 => ['rating' => 1, 'url' => '1'], 20 => ['rating' => 1, 'url' => '1']],
            2 => [30 => ['rating' => 2, 'url' => '2']],
        ];
        $this->assertEquals($expected_result, $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByClosureWhereItemsHaveSingleGroup()
    {
        $data = new Collection([['rating' => 1, 'url' => '1'], ['rating' => 1, 'url' => '1'], ['rating' => 2, 'url' => '2']]);
        $result = $data->groupBy(static function ($item) {
            return $item['rating'];
        });
        $this->assertEquals([1 => [['rating' => 1, 'url' => '1'], ['rating' => 1, 'url' => '1']], 2 => [['rating' => 2, 'url' => '2']]], $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByClosureWhereItemsHaveSingleGroupPreservingKeys()
    {
        $data = new Collection([10 => ['rating' => 1, 'url' => '1'], 20 => ['rating' => 1, 'url' => '1'], 30 => ['rating' => 2, 'url' => '2']]);
        $result = $data->groupBy(static function ($item) {
            return $item['rating'];
        }, true);
        $expected_result = [
            1 => [10 => ['rating' => 1, 'url' => '1'], 20 => ['rating' => 1, 'url' => '1']],
            2 => [30 => ['rating' => 2, 'url' => '2']],
        ];
        $this->assertEquals($expected_result, $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByClosureWhereItemsHaveMultipleGroups()
    {
        $data = new Collection([
            ['user' => 1, 'roles' => ['Role_1', 'Role_3']],
            ['user' => 2, 'roles' => ['Role_1', 'Role_2']],
            ['user' => 3, 'roles' => ['Role_1']],
        ]);
        $result = $data->groupBy(static function ($item) {
            return $item['roles'];
        });
        $expected_result = [
            'Role_1' => [
                ['user' => 1, 'roles' => ['Role_1', 'Role_3']],
                ['user' => 2, 'roles' => ['Role_1', 'Role_2']],
                ['user' => 3, 'roles' => ['Role_1']],
            ],
            'Role_2' => [
                ['user' => 2, 'roles' => ['Role_1', 'Role_2']],
            ],
            'Role_3' => [
                ['user' => 1, 'roles' => ['Role_1', 'Role_3']],
            ],
        ];
        $this->assertEquals($expected_result, $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByClosureWhereItemsHaveMultipleGroupsPreservingKeys()
    {
        $data = new Collection([
            10 => ['user' => 1, 'roles' => ['Role_1', 'Role_3']],
            20 => ['user' => 2, 'roles' => ['Role_1', 'Role_2']],
            30 => ['user' => 3, 'roles' => ['Role_1']],
        ]);
        $result = $data->groupBy(static function ($item) {
            return $item['roles'];
        }, true);
        $expected_result = [
            'Role_1' => [
                10 => ['user' => 1, 'roles' => ['Role_1', 'Role_3']],
                20 => ['user' => 2, 'roles' => ['Role_1', 'Role_2']],
                30 => ['user' => 3, 'roles' => ['Role_1']],
            ],
            'Role_2' => [
                20 => ['user' => 2, 'roles' => ['Role_1', 'Role_2']],
            ],
            'Role_3' => [
                10 => ['user' => 1, 'roles' => ['Role_1', 'Role_3']],
            ],
        ];
        $this->assertEquals($expected_result, $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGroupByMultiLevelAndClosurePreservingKeys()
    {
        $data = new Collection([
            10 => ['user' => 1, 'skilllevel' => 1, 'roles' => ['Role_1', 'Role_3']],
            20 => ['user' => 2, 'skilllevel' => 1, 'roles' => ['Role_1', 'Role_2']],
            30 => ['user' => 3, 'skilllevel' => 2, 'roles' => ['Role_1']],
            40 => ['user' => 4, 'skilllevel' => 2, 'roles' => ['Role_2']],
        ]);
        $result = $data->groupBy([
            'skilllevel',
            static function ($item) {
                return $item['roles'];
            },
        ], true);
        $expected_result = [
            1 => [
                'Role_1' => [
                    10 => ['user' => 1, 'skilllevel' => 1, 'roles' => ['Role_1', 'Role_3']],
                    20 => ['user' => 2, 'skilllevel' => 1, 'roles' => ['Role_1', 'Role_2']],
                ],
                'Role_3' => [
                    10 => ['user' => 1, 'skilllevel' => 1, 'roles' => ['Role_1', 'Role_3']],
                ],
                'Role_2' => [
                    20 => ['user' => 2, 'skilllevel' => 1, 'roles' => ['Role_1', 'Role_2']],
                ],
            ],
            2 => [
                'Role_1' => [
                    30 => ['user' => 3, 'skilllevel' => 2, 'roles' => ['Role_1']],
                ],
                'Role_2' => [
                    40 => ['user' => 4, 'skilllevel' => 2, 'roles' => ['Role_2']],
                ],
            ],
        ];
        $this->assertEquals($expected_result, $result->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testKeyByAttribute()
    {
        $data = new Collection([['rating' => 1, 'name' => '1'], ['rating' => 2, 'name' => '2'], ['rating' => 3, 'name' => '3']]);
        $result = $data->keyBy('rating');
        $this->assertEquals([1 => ['rating' => 1, 'name' => '1'], 2 => ['rating' => 2, 'name' => '2'], 3 => ['rating' => 3, 'name' => '3']], $result->all());
        $result = $data->keyBy(static function ($item) {
            return $item['rating'] * 2;
        });
        $this->assertEquals([2 => ['rating' => 1, 'name' => '1'], 4 => ['rating' => 2, 'name' => '2'], 6 => ['rating' => 3, 'name' => '3']], $result->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testKeyByClosure()
    {
        $data = new Collection([
            ['firstname' => 'suzunone', 'lastname' => 'Otwell', 'locale' => 'US'],
            ['firstname' => 'Lucas', 'lastname' => 'Michot', 'locale' => 'FR'],
        ]);
        $result = $data->keyBy(static function ($item, $key) {
            return strtolower($key . '-' . $item['firstname'] . $item['lastname']);
        });
        $this->assertEquals([
            '0-suzunoneotwell' => ['firstname' => 'suzunone', 'lastname' => 'Otwell', 'locale' => 'US'],
            '1-lucasmichot' => ['firstname' => 'Lucas', 'lastname' => 'Michot', 'locale' => 'FR'],
        ], $result->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testContains()
    {
        $c = new Collection([1, 3, 5]);
        $this->assertTrue($c->contains(1));
        $this->assertFalse($c->contains(2));
        $this->assertTrue($c->contains(static function ($value) {
            return $value < 5;
        }));
        $this->assertFalse($c->contains(static function ($value) {
            return $value > 5;
        }));
        $c = new Collection([['v' => 1], ['v' => 3], ['v' => 5]]);
        $this->assertTrue($c->contains('v', 1));
        $this->assertFalse($c->contains('v', 2));
        $c = new Collection(['date', 'class', (object)['foo' => 50]]);
        $this->assertTrue($c->contains('date'));
        $this->assertTrue($c->contains('class'));
        $this->assertFalse($c->contains('foo'));
        $c = new Collection([['a' => false, 'b' => false], ['a' => true, 'b' => false]]);
        $this->assertTrue($c->contains->a);
        $this->assertFalse($c->contains->b);
        $c = new Collection([
            null, 1, 2,
        ]);
        $this->assertTrue($c->contains(static function ($value) {
            return is_null($value);
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSome()
    {
        $c = new Collection([1, 3, 5]);
        $this->assertTrue($c->some(1));
        $this->assertFalse($c->some(2));
        $this->assertTrue($c->some(static function ($value) {
            return $value < 5;
        }));
        $this->assertFalse($c->some(static function ($value) {
            return $value > 5;
        }));
        $c = new Collection([['v' => 1], ['v' => 3], ['v' => 5]]);
        $this->assertTrue($c->some('v', 1));
        $this->assertFalse($c->some('v', 2));
        $c = new Collection(['date', 'class', (object)['foo' => 50]]);
        $this->assertTrue($c->some('date'));
        $this->assertTrue($c->some('class'));
        $this->assertFalse($c->some('foo'));
        $c = new Collection([['a' => false, 'b' => false], ['a' => true, 'b' => false]]);
        $this->assertTrue($c->some->a);
        $this->assertFalse($c->some->b);
        $c = new Collection([
            null, 1, 2,
        ]);
        $this->assertTrue($c->some(static function ($value) {
            return is_null($value);
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testContainsStrict()
    {
        $c = new Collection([1, 3, 5, '02']);
        $this->assertTrue($c->containsStrict(1));
        $this->assertFalse($c->containsStrict(2));
        $this->assertTrue($c->containsStrict('02'));
        $this->assertTrue($c->containsStrict(static function ($value) {
            return $value < 5;
        }));
        $this->assertFalse($c->containsStrict(static function ($value) {
            return $value > 5;
        }));
        $c = new Collection([['v' => 1], ['v' => 3], ['v' => '04'], ['v' => 5]]);
        $this->assertTrue($c->containsStrict('v', 1));
        $this->assertFalse($c->containsStrict('v', 2));
        $this->assertFalse($c->containsStrict('v', 4));
        $this->assertTrue($c->containsStrict('v', '04'));
        $c = new Collection(['date', 'class', (object)['foo' => 50], '']);
        $this->assertTrue($c->containsStrict('date'));
        $this->assertTrue($c->containsStrict('class'));
        $this->assertFalse($c->containsStrict('foo'));
        $this->assertFalse($c->containsStrict(null));
        $this->assertTrue($c->containsStrict(''));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testContainsWithOperator()
    {
        $c = new Collection([['v' => 1], ['v' => 3], ['v' => '4'], ['v' => 5]]);
        $this->assertTrue($c->contains('v', '=', 4));
        $this->assertTrue($c->contains('v', '==', 4));
        $this->assertFalse($c->contains('v', '===', 4));
        $this->assertTrue($c->contains('v', '>', 4));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGettingSumFromCollection()
    {
        $c = new Collection([(object)['foo' => 50], (object)['foo' => 50]]);
        $this->assertEquals(100, $c->sum('foo'));
        $c = new Collection([(object)['foo' => 50], (object)['foo' => 50]]);
        $this->assertEquals(100, $c->sum(static function ($i) {
            return $i->foo;
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCanSumValuesWithoutACallback()
    {
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(15, $c->sum());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGettingSumFromEmptyCollection()
    {
        $c = new Collection;
        $this->assertEquals(0, $c->sum('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testValueRetrieverAcceptsDotNotation()
    {
        $c = new Collection([
            (object)['id' => 1, 'foo' => ['bar' => 'B']], (object)['id' => 2, 'foo' => ['bar' => 'A']],
        ]);
        $c = $c->sortBy('foo.bar');
        $this->assertEquals([2, 1], $c->pluck('id')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPullRetrievesItemFromCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->pull(0));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPullRemovesItemFromCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $c->pull(0);
        $this->assertEquals([1 => 'bar'], $c->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPullReturnsDefault()
    {
        $c = new Collection([]);
        $value = $c->pull(0, 'foo');
        $this->assertEquals('foo', $value);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testRejectRemovesElementsPassingTruthTest()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo'], $c->reject('bar')->values()->all());
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo'], $c->reject(static function ($v) {
            return $v == 'bar';
        })->values()->all());
        $c = new Collection(['foo', null]);
        $this->assertEquals(['foo'], $c->reject(null)->values()->all());
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->reject('baz')->values()->all());
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $c->reject(static function ($v) {
            return $v == 'baz';
        })->values()->all());
        $c = new Collection(['id' => 1, 'primary' => 'foo', 'secondary' => 'bar']);
        $this->assertEquals(['primary' => 'foo', 'secondary' => 'bar'], $c->reject(static function ($item, $key) {
            return $key == 'id';
        })->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSearchReturnsIndexOfFirstFoundItem()
    {
        $c = new Collection([1, 2, 3, 4, 5, 2, 5, 'foo' => 'bar']);
        $this->assertEquals(1, $c->search(2));
        $this->assertEquals('foo', $c->search('bar'));
        $this->assertEquals(4, $c->search(static function ($value) {
            return $value > 4;
        }));
        $this->assertEquals('foo', $c->search(static function ($value) {
            return !is_numeric($value);
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSearchReturnsFalseWhenItemIsNotFound()
    {
        $c = new Collection([1, 2, 3, 4, 5, 'foo' => 'bar']);
        $this->assertFalse($c->search(6));
        $this->assertFalse($c->search('foo'));
        $this->assertFalse($c->search(static function ($value) {
            return $value < 1 && is_numeric($value);
        }));
        $this->assertFalse($c->search(static function ($value) {
            return $value == 'nope';
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testKeys()
    {
        $c = new Collection(['name' => 'suzunone', 'tool' => 'gitlive']);
        $this->assertEquals(['name', 'tool'], $c->keys()->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPaginate()
    {
        $c = new Collection(['one', 'two', 'three', 'four']);
        $this->assertEquals(['one', 'two'], $c->forPage(0, 2)->all());
        $this->assertEquals(['one', 'two'], $c->forPage(1, 2)->all());
        $this->assertEquals([2 => 'three', 3 => 'four'], $c->forPage(2, 2)->all());
        $this->assertEquals([], $c->forPage(3, 2)->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPrepend()
    {
        $c = new Collection(['one', 'two', 'three', 'four']);
        $this->assertEquals(['zero', 'one', 'two', 'three', 'four'], $c->prepend('zero')->all());
        $c = new Collection(['one' => 1, 'two' => 2]);
        $this->assertEquals(['zero' => 0, 'one' => 1, 'two' => 2], $c->prepend(0, 'zero')->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testZip()
    {
        $c = new Collection([1, 2, 3]);
        $c = $c->zip(new Collection([4, 5, 6]));
        $this->assertInstanceOf(Collection::class, $c);
        $this->assertInstanceOf(Collection::class, $c[0]);
        $this->assertInstanceOf(Collection::class, $c[1]);
        $this->assertInstanceOf(Collection::class, $c[2]);
        $this->assertCount(3, $c);
        $this->assertEquals([1, 4], $c[0]->all());
        $this->assertEquals([2, 5], $c[1]->all());
        $this->assertEquals([3, 6], $c[2]->all());
        $c = new Collection([1, 2, 3]);
        $c = $c->zip([4, 5, 6], [7, 8, 9]);
        $this->assertCount(3, $c);
        $this->assertEquals([1, 4, 7], $c[0]->all());
        $this->assertEquals([2, 5, 8], $c[1]->all());
        $this->assertEquals([3, 6, 9], $c[2]->all());
        $c = new Collection([1, 2, 3]);
        $c = $c->zip([4, 5, 6], [7]);
        $this->assertCount(3, $c);
        $this->assertEquals([1, 4, 7], $c[0]->all());
        $this->assertEquals([2, 5, null], $c[1]->all());
        $this->assertEquals([3, 6, null], $c[2]->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPadPadsArrayWithValue()
    {
        $c = new Collection([1, 2, 3]);
        $c = $c->pad(4, 0);
        $this->assertEquals([1, 2, 3, 0], $c->all());
        $c = new Collection([1, 2, 3, 4, 5]);
        $c = $c->pad(4, 0);
        $this->assertEquals([1, 2, 3, 4, 5], $c->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGettingMaxItemsFromCollection()
    {
        $c = new Collection([(object)['foo' => 10], (object)['foo' => 20]]);
        $this->assertEquals(20, $c->max(static function ($item) {
            return $item->foo;
        }));
        $this->assertEquals(20, $c->max('foo'));
        $this->assertEquals(20, $c->max->foo);
        $c = new Collection([['foo' => 10], ['foo' => 20]]);
        $this->assertEquals(20, $c->max('foo'));
        $this->assertEquals(20, $c->max->foo);
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(5, $c->max());
        $c = new Collection;
        $this->assertNull($c->max());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGettingMinItemsFromCollection()
    {
        $c = new Collection([(object)['foo' => 10], (object)['foo' => 20]]);
        $this->assertEquals(10, $c->min(static function ($item) {
            return $item->foo;
        }));
        $this->assertEquals(10, $c->min('foo'));
        $this->assertEquals(10, $c->min->foo);
        $c = new Collection([['foo' => 10], ['foo' => 20]]);
        $this->assertEquals(10, $c->min('foo'));
        $this->assertEquals(10, $c->min->foo);
        $c = new Collection([['foo' => 10], ['foo' => 20], ['foo' => null]]);
        $this->assertEquals(10, $c->min('foo'));
        $this->assertEquals(10, $c->min->foo);
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(1, $c->min());
        $c = new Collection([1, null, 3, 4, 5]);
        $this->assertEquals(1, $c->min());
        $c = new Collection([0, 1, 2, 3, 4]);
        $this->assertEquals(0, $c->min());
        $c = new Collection;
        $this->assertNull($c->min());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testOnly()
    {
        $data = new Collection(['first' => 'suzunone', 'last' => 'Otwell', 'email' => 'suzunoneotwell@gmail.com']);
        $this->assertEquals($data->all(), $data->only(null)->all());
        $this->assertEquals(['first' => 'suzunone'], $data->only(['first', 'missing'])->all());
        $this->assertEquals(['first' => 'suzunone'], $data->only('first', 'missing')->all());
        $this->assertEquals(['first' => 'suzunone'], $data->only(collect(['first', 'missing']))->all());
        $this->assertEquals(['first' => 'suzunone', 'email' => 'suzunoneotwell@gmail.com'], $data->only(['first', 'email'])->all());
        $this->assertEquals(['first' => 'suzunone', 'email' => 'suzunoneotwell@gmail.com'], $data->only('first', 'email')->all());
        $this->assertEquals(['first' => 'suzunone', 'email' => 'suzunoneotwell@gmail.com'], $data->only(collect(['first', 'email']))->all());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGettingAvgItemsFromCollection()
    {
        $c = new Collection([(object)['foo' => 10], (object)['foo' => 20]]);
        $this->assertEquals(15, $c->avg(static function ($item) {
            return $item->foo;
        }));
        $this->assertEquals(15, $c->avg('foo'));
        $this->assertEquals(15, $c->avg->foo);
        $c = new Collection([(object)['foo' => 10], (object)['foo' => 20], (object)['foo' => null]]);
        $this->assertEquals(15, $c->avg(static function ($item) {
            return $item->foo;
        }));
        $this->assertEquals(15, $c->avg('foo'));
        $this->assertEquals(15, $c->avg->foo);
        $c = new Collection([['foo' => 10], ['foo' => 20]]);
        $this->assertEquals(15, $c->avg('foo'));
        $this->assertEquals(15, $c->avg->foo);
        $c = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(3, $c->avg());
        $c = new Collection;
        $this->assertEquals(0, $c->avg());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testJsonSerialize()
    {
        $c = new Collection([
            new TestArrayableObject,
            new TestJsonableObject,
            new TestJsonSerializeObject,
            'baz',
        ]);
        $this->assertSame([
            ['foo' => 'bar'],
            ['foo' => 'bar'],
            ['foo' => 'bar'],
            'baz',
        ], $c->jsonSerialize());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCombineWithArray()
    {
        $expected = [
            1 => 4,
            2 => 5,
            3 => 6,
        ];
        $c = new Collection(array_keys($expected));
        $actual = $c->combine(array_values($expected))->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCombineWithCollection()
    {
        $expected = [
            1 => 4,
            2 => 5,
            3 => 6,
        ];
        $keyCollection = new Collection(array_keys($expected));
        $valueCollection = new Collection(array_values($expected));
        $actual = $keyCollection->combine($valueCollection)->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConcatWithArray()
    {
        $expected = [
            0 => 4,
            1 => 5,
            2 => 6,
            3 => 'a',
            4 => 'b',
            5 => 'c',
            6 => 'Jonny',
            7 => 'from',
            8 => 'Laroe',
            9 => 'Jonny',
            10 => 'from',
            11 => 'Laroe',
        ];
        $collection = new Collection([4, 5, 6]);
        $collection = $collection->concat(['a', 'b', 'c']);
        $collection = $collection->concat(['who' => 'Jonny', 'preposition' => 'from', 'where' => 'Laroe']);
        $actual = $collection->concat(['who' => 'Jonny', 'preposition' => 'from', 'where' => 'Laroe'])->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testConcatWithCollection()
    {
        $expected = [
            0 => 4,
            1 => 5,
            2 => 6,
            3 => 'a',
            4 => 'b',
            5 => 'c',
            6 => 'Jonny',
            7 => 'from',
            8 => 'Laroe',
            9 => 'Jonny',
            10 => 'from',
            11 => 'Laroe',
        ];
        $firstCollection = new Collection([4, 5, 6]);
        $secondCollection = new Collection(['a', 'b', 'c']);
        $thirdCollection = new Collection(['who' => 'Jonny', 'preposition' => 'from', 'where' => 'Laroe']);
        $firstCollection = $firstCollection->concat($secondCollection);
        $firstCollection = $firstCollection->concat($thirdCollection);
        $actual = $firstCollection->concat($thirdCollection)->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testReduce()
    {
        $data = new Collection([1, 2, 3]);
        $this->assertEquals(6, $data->reduce(static function ($carry, $element) {
            return $carry += $element;
        }));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @covers \GitLive\Support\Collection
     */
    public function testRandomThrowsAnExceptionUsingAmountBiggerThanCollectionSize()
    {
        $data = new Collection([1, 2, 3]);
        $data->random(4);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPipe()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals(6, $collection->pipe(static function ($collection) {
            return $collection->sum();
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMedianValueWithArrayCollection()
    {
        $collection = new Collection([1, 2, 2, 4]);
        $this->assertEquals(2, $collection->median());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMedianValueByKey()
    {
        $collection = new Collection([
            (object)['foo' => 1],
            (object)['foo' => 2],
            (object)['foo' => 2],
            (object)['foo' => 4],
        ]);
        $this->assertEquals(2, $collection->median('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMedianOnCollectionWithNull()
    {
        $collection = new Collection([
            (object)['foo' => 1],
            (object)['foo' => 2],
            (object)['foo' => 4],
            (object)['foo' => null],
        ]);
        $this->assertEquals(2, $collection->median('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testEvenMedianCollection()
    {
        $collection = new Collection([
            (object)['foo' => 0],
            (object)['foo' => 3],
        ]);
        $this->assertEquals(1.5, $collection->median('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMedianOutOfOrderCollection()
    {
        $collection = new Collection([
            (object)['foo' => 0],
            (object)['foo' => 5],
            (object)['foo' => 3],
        ]);
        $this->assertEquals(3, $collection->median('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMedianOnEmptyCollectionReturnsNull()
    {
        $collection = new Collection;
        $this->assertNull($collection->median());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testModeOnNullCollection()
    {
        $collection = new Collection;
        $this->assertNull($collection->mode());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testMode()
    {
        $collection = new Collection([1, 2, 3, 4, 4, 5]);
        $this->assertEquals([4], $collection->mode());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testModeValueByKey()
    {
        $collection = new Collection([
            (object)['foo' => 1],
            (object)['foo' => 1],
            (object)['foo' => 2],
            (object)['foo' => 4],
        ]);
        $this->assertEquals([1], $collection->mode('foo'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWithMultipleModeValues()
    {
        $collection = new Collection([1, 2, 2, 1]);
        $this->assertEquals([1, 2], $collection->mode());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSliceOffset()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6, 7, 8], $collection->slice(3)->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSliceNegativeOffset()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([6, 7, 8], $collection->slice(-3)->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSliceOffsetAndLength()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6], $collection->slice(3, 3)->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSliceOffsetAndNegativeLength()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6, 7], $collection->slice(3, -1)->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSliceNegativeOffsetAndLength()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([4, 5, 6], $collection->slice(-5, 3)->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSliceNegativeOffsetAndNegativeLength()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8]);
        $this->assertEquals([3, 4, 5, 6], $collection->slice(-6, -2)->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCollectionFromTraversable()
    {
        $collection = new Collection(new ArrayObject([1, 2, 3]));
        $this->assertEquals([1, 2, 3], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testCollectionFromTraversableWithKeys()
    {
        $collection = new Collection(new ArrayObject(['foo' => 1, 'bar' => 2, 'baz' => 3]));
        $this->assertEquals(['foo' => 1, 'bar' => 2, 'baz' => 3], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitCollectionWithADivisableCount()
    {
        $collection = new Collection(['a', 'b', 'c', 'd']);
        $this->assertEquals(
            [['a', 'b'], ['c', 'd']],
            $collection->split(2)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $this->assertEquals(
            [[1, 2, 3, 4, 5], [6, 7, 8, 9, 10]],
            $collection->split(2)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitCollectionWithAnUndivisableCount()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $this->assertEquals(
            [['a', 'b'], ['c']],
            $collection->split(2)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitCollectionWithCountLessThenDivisor()
    {
        $collection = new Collection(['a']);
        $this->assertEquals(
            [['a']],
            $collection->split(2)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitCollectionIntoThreeWithCountOfFour()
    {
        $collection = new Collection(['a', 'b', 'c', 'd']);
        $this->assertEquals(
            [['a', 'b'], ['c'], ['d']],
            $collection->split(3)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitCollectionIntoThreeWithCountOfFive()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e']);
        $this->assertEquals(
            [['a', 'b'], ['c', 'd'], ['e']],
            $collection->split(3)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitCollectionIntoSixWithCountOfTen()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j']);
        $this->assertEquals(
            [['a', 'b'], ['c', 'd'], ['e', 'f'], ['g', 'h'], ['i'], ['j']],
            $collection->split(6)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testSplitEmptyCollection()
    {
        $collection = new Collection;
        $this->assertEquals(
            [],
            $collection->split(2)->map(static function (Collection $chunk) {
                return $chunk->values()->toArray();
            })->toArray()
        );
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderCollectionGroupBy()
    {
        $collection = collect([
            new TestSupportCollectionHigherOrderItem,
            new TestSupportCollectionHigherOrderItem('SUZUNONE'),
            new TestSupportCollectionHigherOrderItem('foo'),
        ]);
        $this->assertEquals([
            'suzunone' => [$collection[0]],
            'SUZUNONE' => [$collection[1]],
            'foo' => [$collection[2]],
        ], $collection->groupBy->name->toArray());
        $this->assertEquals([
            'SUZUNONE' => [$collection[0], $collection[1]],
            'FOO' => [$collection[2]],
        ], $collection->groupBy->uppercase()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderCollectionMap()
    {
        $person1 = (object)['name' => 'suzunone'];
        $person2 = (object)['name' => 'Yaz'];
        $collection = collect([$person1, $person2]);
        $this->assertEquals(['suzunone', 'Yaz'], $collection->map->name->toArray());
        $collection = collect([new TestSupportCollectionHigherOrderItem, new TestSupportCollectionHigherOrderItem]);
        $this->assertEquals(['SUZUNONE', 'SUZUNONE'], $collection->each->uppercase()->map->name->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderCollectionMapFromArrays()
    {
        $person1 = ['name' => 'suzunone'];
        $person2 = ['name' => 'Yaz'];
        $collection = collect([$person1, $person2]);
        $this->assertEquals(['suzunone', 'Yaz'], $collection->map->name->toArray());
        $collection = collect([new TestSupportCollectionHigherOrderItem, new TestSupportCollectionHigherOrderItem]);
        $this->assertEquals(['SUZUNONE', 'SUZUNONE'], $collection->each->uppercase()->map->name->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPartition()
    {
        $collection = new Collection(range(1, 10));
        [$firstPartition, $secondPartition] = $collection->partition(static function ($i) {
            return $i <= 5;
        });
        $this->assertEquals([1, 2, 3, 4, 5], $firstPartition->values()->toArray());
        $this->assertEquals([6, 7, 8, 9, 10], $secondPartition->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPartitionCallbackWithKey()
    {
        $collection = new Collection(['zero', 'one', 'two', 'three']);
        [$even, $odd] = $collection->partition(static function ($item, $index) {
            return $index % 2 === 0;
        });
        $this->assertEquals(['zero', 'two'], $even->values()->toArray());
        $this->assertEquals(['one', 'three'], $odd->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPartitionByKey()
    {
        $courses = new Collection([
            ['free' => true, 'title' => 'Basic'], ['free' => false, 'title' => 'Premium'],
        ]);
        [$free, $premium] = $courses->partition('free');
        $this->assertSame([['free' => true, 'title' => 'Basic']], $free->values()->toArray());
        $this->assertSame([['free' => false, 'title' => 'Premium']], $premium->values()->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPartitionWithOperators()
    {
        $collection = new Collection([
            ['name' => 'Tim', 'age' => 17],
            ['name' => 'Agatha', 'age' => 62],
            ['name' => 'Kristina', 'age' => 33],
            ['name' => 'Tim', 'age' => 41],
        ]);
        [$tims, $others] = $collection->partition('name', 'Tim');
        $this->assertEquals($tims->values()->all(), [
            ['name' => 'Tim', 'age' => 17],
            ['name' => 'Tim', 'age' => 41],
        ]);
        $this->assertEquals($others->values()->all(), [
            ['name' => 'Agatha', 'age' => 62],
            ['name' => 'Kristina', 'age' => 33],
        ]);
        [$adults, $minors] = $collection->partition('age', '>=', 18);
        $this->assertEquals($adults->values()->all(), [
            ['name' => 'Agatha', 'age' => 62],
            ['name' => 'Kristina', 'age' => 33],
            ['name' => 'Tim', 'age' => 41],
        ]);
        $this->assertEquals($minors->values()->all(), [
            ['name' => 'Tim', 'age' => 17],
        ]);
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPartitionPreservesKeys()
    {
        $courses = new Collection([
            'a' => ['free' => true], 'b' => ['free' => false], 'c' => ['free' => true],
        ]);
        [$free, $premium] = $courses->partition('free');
        $this->assertSame(['a' => ['free' => true], 'c' => ['free' => true]], $free->toArray());
        $this->assertSame(['b' => ['free' => false]], $premium->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPartitionEmptyCollection()
    {
        $collection = new Collection;
        $this->assertCount(2, $collection->partition(static function () {
            return true;
        }));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHigherOrderPartition()
    {
        $courses = new Collection([
            'a' => ['free' => true], 'b' => ['free' => false], 'c' => ['free' => true],
        ]);
        [$free, $premium] = $courses->partition->free;
        $this->assertSame(['a' => ['free' => true], 'c' => ['free' => true]], $free->toArray());
        $this->assertSame(['b' => ['free' => false]], $premium->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testTap()
    {
        $collection = new Collection([1, 2, 3]);
        $fromTap = [];
        $collection = $collection->tap(static function ($collection) use (&$fromTap) {
            $fromTap = $collection->slice(0, 1)->toArray();
        });
        $this->assertSame([1], $fromTap);
        $this->assertSame([1, 2, 3], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhen()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->when('adam', static function ($collection, $newName) {
            return $collection->push($newName);
        });
        $this->assertSame(['michael', 'tom', 'adam'], $collection->toArray());
        $collection = new Collection(['michael', 'tom']);
        $collection->when(false, static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['michael', 'tom'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhenDefault()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->when(false, static function ($collection) {
            return $collection->push('adam');
        }, static function ($collection) {
            return $collection->push('suzunone');
        });
        $this->assertSame(['michael', 'tom', 'suzunone'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhenEmpty()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->whenEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['michael', 'tom'], $collection->toArray());
        $collection = new Collection;
        $collection->whenEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['adam'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhenEmptyDefault()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->whenEmpty(static function ($collection) {
            return $collection->push('adam');
        }, static function ($collection) {
            return $collection->push('suzunone');
        });
        $this->assertSame(['michael', 'tom', 'suzunone'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhenNotEmpty()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->whenNotEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['michael', 'tom', 'adam'], $collection->toArray());
        $collection = new Collection;
        $collection->whenNotEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame([], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testWhenNotEmptyDefault()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->whenNotEmpty(static function ($collection) {
            return $collection->push('adam');
        }, static function ($collection) {
            return $collection->push('suzunone');
        });
        $this->assertSame(['michael', 'tom', 'adam'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnless()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unless(false, static function ($collection) {
            return $collection->push('caleb');
        });
        $this->assertSame(['michael', 'tom', 'caleb'], $collection->toArray());
        $collection = new Collection(['michael', 'tom']);
        $collection->unless(true, static function ($collection) {
            return $collection->push('caleb');
        });
        $this->assertSame(['michael', 'tom'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnlessDefault()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unless(true, static function ($collection) {
            return $collection->push('caleb');
        }, static function ($collection) {
            return $collection->push('suzunone');
        });
        $this->assertSame(['michael', 'tom', 'suzunone'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnlessEmpty()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unlessEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['michael', 'tom', 'adam'], $collection->toArray());
        $collection = new Collection;
        $collection->unlessEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame([], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnlessEmptyDefault()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unlessEmpty(static function ($collection) {
            return $collection->push('adam');
        }, static function ($collection) {
            return $collection->push('suzunone');
        });
        $this->assertSame(['michael', 'tom', 'adam'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnlessNotEmpty()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unlessNotEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['michael', 'tom'], $collection->toArray());
        $collection = new Collection;
        $collection->unlessNotEmpty(static function ($collection) {
            return $collection->push('adam');
        });
        $this->assertSame(['adam'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testUnlessNotEmptyDefault()
    {
        $collection = new Collection(['michael', 'tom']);
        $collection->unlessNotEmpty(static function ($collection) {
            return $collection->push('adam');
        }, static function ($collection) {
            return $collection->push('suzunone');
        });
        $this->assertSame(['michael', 'tom', 'suzunone'], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testHasReturnsValidResults()
    {
        $collection = new Collection(['foo' => 'one', 'bar' => 'two', 1 => 'three']);
        $this->assertTrue($collection->has('foo'));
        $this->assertTrue($collection->has('foo', 'bar', 1));
        $this->assertFalse($collection->has('foo', 'bar', 1, 'baz'));
        $this->assertFalse($collection->has('baz'));
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testPutAddsItemToCollection()
    {
        $collection = new Collection;
        $this->assertSame([], $collection->toArray());
        $collection->put('foo', 1);
        $this->assertSame(['foo' => 1], $collection->toArray());
        $collection->put('bar', ['nested' => 'two']);
        $this->assertSame(['foo' => 1, 'bar' => ['nested' => 'two']], $collection->toArray());
        $collection->put('foo', 3);
        $this->assertSame(['foo' => 3, 'bar' => ['nested' => 'two']], $collection->toArray());
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testItThrowsExceptionWhenTryingToAccessNoProxyProperty()
    {
        $collection = new Collection;
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Property [foo] does not exist on this collection instance.');
        $collection->foo;
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function testGetWithNullReturnsNull()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertNull($collection->get(null));
    }
}

class TestSupportCollectionHigherOrderItem
{
    public $name;

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $name
     */
    public function __construct($name = 'suzunone')
    {
        $this->name = $name;
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function uppercase()
    {
        return $this->name = strtoupper($this->name);
    }
}

class TestAccessorEloquentTestStub
{
    protected $attributes = [];

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $attributes
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        $accessor = 'get' . lcfirst($attribute) . 'Attribute';
        if (method_exists($this, $accessor)) {
            return $this->{$accessor}();
        }

        return $this->{$attribute};
    }

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $attribute
     * @return bool
     */
    public function __isset($attribute)
    {
        $accessor = 'get' . lcfirst($attribute) . 'Attribute';
        if (method_exists($this, $accessor)) {
            return !is_null($this->{$accessor}());
        }

        return isset($this->{$attribute});
    }

    /**
     * @covers \GitLive\Support\Collection
     */
    public function getSomeAttribute()
    {
        return $this->attributes['some'];
    }
}

class TestArrayAccessImplementation implements ArrayAccess
{
    private $arr;

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $arr
     */
    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->arr[$offset]);
    }

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->arr[$offset];
    }

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->arr[$offset] = $value;
    }

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->arr[$offset]);
    }
}

class TestArrayableObject implements Arrayable
{
    /**
     * @covers \GitLive\Support\Collection
     */
    public function toArray(): array
    {
        return ['foo' => 'bar'];
    }
}

class TestJsonableObject implements Jsonable
{
    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return '{"foo":"bar"}';
    }
}

class TestJsonSerializeObject implements JsonSerializable
{
    /**
     * @covers \GitLive\Support\Collection
     */
    public function jsonSerialize()
    {
        return ['foo' => 'bar'];
    }
}

class TestCollectionMapIntoObject
{
    public $value;

    /**
     * @covers \GitLive\Support\Collection
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}

class TestCollectionSubclass extends Collection
{
    //
}
