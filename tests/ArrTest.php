<?php

declare(strict_types=1);

namespace Belca\Support\Tests;

use \stdClass;
use Belca\Support\Arr;
use PHPUnit\Framework\TestCase;

final class ArrTest extends TestCase
{
    /**
     * @param  array  $expected
     * @param  array  $array
     * @param  bool   $resetIndex
     *
     * @dataProvider removeArraysProvider
     */
    public function testRemoveArrays(array $expected, array $array, $resetIndex = false)
    {
        $this->assertEquals($expected, Arr::removeArrays($array, $resetIndex));
    }

    public function removeArraysProvider(): array
    {
        return [
            [
                [
                    1,
                    2,
                    3,
                    'four' => 4,
                    'five' => 5,
                    3 => 7,
                    'eight' => 8,
                    4 => 9,
                    'object' => new stdClass(),
                ],
                [
                    1,
                    2,
                    3,
                    'four' => 4,
                    'five' => 5,
                    'matrix' => [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    7,
                    'eight' => 8,
                    9,
                    'symbols' => ['a', 'b', 'c'],
                    'object' => new stdClass(),
                ]
            ],
            [
                [1, 2, 3, 4, 5, 7, 8, 9, new stdClass()],
                [
                    1,
                    2,
                    3,
                    'four' => 4,
                    'five' => 5,
                    'matrix' => [
                        [1, 2, 3],
                        [4, 5, 6],
                        [7, 8, 9],
                    ],
                    7,
                    'eight' => 8,
                    9,
                    'symbols' => ['a', 'b', 'c'],
                    'object' => new stdClass(),
                ],
                'resetIndex' => true,
            ],
            [
                [],
                [],
            ],
            [
                [],
                [[]],
            ],
        ];
    }

    public function testConcatArrayFirstTest()
    {
        $source = [];
        $array = [];
        $output = [];

        // Filling. Number keys
        for ($i = 0; $i < 20; $i++) {
            $source[$i] = $i * 10;
        }

        for ($i = 20; $i < 30; $i++) {
            $array[$i] = $i + 10;
        }

        $output = array_merge($source, $array);

        // Normal data
        $result = $source; // the result is here
        Arr::concatArray($result, $array);

        $this->assertEquals($result, $output);
    }

    public function testConcatArrayWithReplaceValues()
    {
        $source = [];
        $array = [];

        // Filling. String keys
        for ($i = 0; $i < 20; $i++) {
            $source["a$i"] = $i * 10;
        }

        for ($i = 10; $i < 20; $i++) {
            $array["a$i"] = $i + 10;
        }

        $output = array_merge($source, $array);

        $result = $source; // the result is here
        Arr::concatArray($result, $array);

        $this->assertEquals($result, $output);
    }

    /**
     * @param  array  $expected
     * @param  array  $source
     * @param  array  $arrays
     *
     * @dataProvider pushArrayProvider
     */
    public function testPushArray(array $expected, array $source, array $arrays)
    {
        $sourceArray = $source;

        Arr::pushArray($source, ...$arrays);
        $this->assertEquals($expected, $source);

        if (count($expected)) {
            $this->assertNotEquals($sourceArray, $source);
        }
    }

    public function pushArrayProvider(): array
    {
        return [
            [
                [1, 2, 3, 4, 5],
                [1, 2, 3],
                [
                    [4, 5]
                ]
            ],
            [
                ['key' => 3, 1, 2, 4, 5],
                [2 => 1, 2, 'key' => 3],
                [
                    [4, 5]
                ]
            ],
            [
                [1, 2, 3, 4, 5],
                [1, 2, 3],
                [
                    [4],
                    [5]
                ]
            ],
            [
                [],
                [],
                [
                    []
                ],
            ]
        ];
    }

    /**
     * @param  array $array
     *
     * @dataProvider isFirstLastWithIntKeysTrueProvider
     */
    public function testisFirstLastWithIntKeys(array $array)
    {
        $this->assertTrue(Arr::isFirstLastWithIntKeys($array));
    }

    public function isFirstLastWithIntKeysTrueProvider(): array
    {
        return [
            [[1, 2, 3, 4, 5, 6, 7, 8, 10]],
            [[1]],
            [[null]],
            [[1, '2', '3', 4]],
            [['1', 2, 3, '4']],
            [['1' => 1, 2, 3, '4' => 4]],
            [[-1 => 1, 2 => 2]],
            [[0 => 1, 1 => 2, 2 => 3, 4 => 4]],
            [['0' => 1, 1 => 2, 2 => 3, 4 => 4]],
            [[0 => 1, 1 => 2, 2 => 3, '4' => 4]],
            [[0.5 => 1, 1 => 2, 2 => 3, 3.5 => 4]],
            [[false => 1, 1 => 2, 2 => 3, 4 => 4]],
            [[true => 1, 0 => 2, 2 => 3, 4 => 4]],
            [[0 => 1, 0 => 2, 2 => 3, false => 4]],
            [[50 => 1, 2 => 3, 0 => 4]],
            [[50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]],
        ];
    }

    /**
     * @param  array $array
     *
     * @dataProvider isFirstLastWithIntKeysFalseProvider
     */
    public function testisFirstLastWithIntKeysFalse(array $array)
    {
        $this->assertFalse(Arr::isFirstLastWithIntKeys($array));
    }

    public function isFirstLastWithIntKeysFalseProvider(): array
    {
        return [
            [
                []
            ],
            [
                ['a0' => 1, 1 => 2, 2 => 3, 4 => 4]
            ],
            [
                [0 => 1, 1 => 2, 2 => 3, 'a4' => 4]
            ],
            [
                [null => 1, 0 => 2, 2 => 3, 4 => 4]
            ]
        ];
    }

    public function testIsArrayWithIntKeys()
    {
        $array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
        $array2 = []; // false
        $array3 = [1]; // true
        $array5 = [null]; // true
        $array6 = [1, '2', '3', 4]; // true
        $array7 = ['1', 2, 3, '4']; // true
        $array8 = ['1' => 1, 2, 3, '4' => 4]; // true
        $array9 = [-1 => 1, 2 => 2]; // true
        $array10 = [0 => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array11 = ['0' => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array11 = [0 => 1, 1 => 2, 2 => 3, '4' => 4]; // true
        $array12 = ['a0' => 1, 1 => 2, 2 => 3, 4 => 4]; // false
        $array13 = [0 => 1, 1 => 2, 2 => 3, 'a4' => 4]; // false
        $array14 = [0.5 => 1, 1 => 2, 2 => 3, 3.5 => 4]; // true
        $array15 = [false => 1, 1 => 2, 2 => 3, 4 => 4]; // true
        $array16 = [true => 1, 0 => 2, 2 => 3, 4 => 4]; // true
        $array17 = [null => 1, 0 => 2, 2 => 3, 4 => 4]; // false
        $array18 = [0 => 1, 0 => 2, 2 => 3, false => 4]; // true
        $array19 = [50 => 1, 2 => 3, 0 => 4]; // true
        $array20 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false
        $array21 = ['a50' => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false
        $array22 = [50 => 1, 2 => 3, 3 => 4, 'a0' => 1]; // false

        $this->assertTrue(Arr::isIntKeys($array1));
        $this->assertFalse(Arr::isIntKeys($array2));
        $this->assertTrue(Arr::isIntKeys($array3));
        $this->assertTrue(Arr::isIntKeys($array5));
        $this->assertTrue(Arr::isIntKeys($array6));
        $this->assertTrue(Arr::isIntKeys($array7));
        $this->assertTrue(Arr::isIntKeys($array8));
        $this->assertTrue(Arr::isIntKeys($array9));
        $this->assertTrue(Arr::isIntKeys($array10));
        $this->assertTrue(Arr::isIntKeys($array11));
        $this->assertFalse(Arr::isIntKeys($array12));
        $this->assertFalse(Arr::isIntKeys($array13));
        $this->assertTrue(Arr::isIntKeys($array14));
        $this->assertTrue(Arr::isIntKeys($array15));
        $this->assertTrue(Arr::isIntKeys($array16));
        $this->assertFalse(Arr::isIntKeys($array17));
        $this->assertTrue(Arr::isIntKeys($array18));
        $this->assertTrue(Arr::isIntKeys($array19));
        $this->assertFalse(Arr::isIntKeys($array20));
        $this->assertFalse(Arr::isIntKeys($array21));
        $this->assertFalse(Arr::isIntKeys($array22));
    }

    public function testRemoveNullRecurcive()
    {
        $array1 = [];
        $result1 = [];

        $array2 = null;
        $result2 = null;

        $array3 = [1, -2, 'a3', '4', 0, ''];
        $result3 = [1, -2, 'a3', '4', 0, ''];

        $array4 = [1, -2, 'a3', null];
        $result4 = [1, -2, 'a3'];

        $array5 = [1, -2, 0, '', []];
        $result5 = [1, -2, 0, '', []];

        $array6 = [1, -2, 'a3' => [1, 2, 3], null];
        $result6 = [1, -2, 'a3' => [1, 2, 3]];

        $array7 = [
            1,
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [],
                ]
            ],
            null
        ];
        $result7 = [1, -2, 'a3' => [1, 2, 'a3.3' => [1, 2, 3, 3 => []]]];

        $array8 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, [], null],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           null,
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            null,
            '',
            0,
            false,
        ];
        $result8 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, []],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            4 => '',
            5 => 0,
            6 => false,
        ];

        $array9 = [
            1 => [
                1,
                2,
                3 => [1, 2, 3, 4, [], null],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    4 => [
                        1,
                        2,
                        3,
                        4 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           null,
                           12 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            null,
            '',
            0,
            false,
        ];
        $result9 = [
            1 => [
                1,
                2,
                2 => [1, 2, 3, 4, []],
                4,
                '',
            ],
            -2,
            'a3' => [
                1,
                2,
                'a3.3' => [
                    1,
                    2,
                    3,
                    3 => [
                        1,
                        2,
                        3,
                        3 => [
                           false,
                           2,
                           true,
                           '',
                           ' ',
                           5 => [1, 2, 0],
                        ]
                    ],
                ]
            ],
            4 => '',
            5 => 0,
            6 => false,
        ];

        $this->assertEquals(Arr::removeNullRecurcive($array1), $result1);
        $this->assertEquals(Arr::removeNullRecurcive($array2), $result2);
        $this->assertEquals(Arr::removeNullRecurcive($array3), $result3);
        $this->assertEquals(Arr::removeNullRecurcive($array4), $result4);
        $this->assertEquals(Arr::removeNullRecurcive($array5), $result5);
        $this->assertEquals(Arr::removeNullRecurcive($array6), $result6);
        $this->assertEquals(Arr::removeNullRecurcive($array7), $result7);
        $this->assertEquals(Arr::removeNullRecurcive($array8, false), $result8);
        $this->assertEquals(Arr::removeNullRecurcive($array9, true), $result9);
    }

    /**
     * @param  mixed  $expected
     * @param  mixed  $array
     * @param  bool   $resetIndex
     *
     * @dataProvider removeEmptyRecurciveProvider
     */
    public function testRemoveEmptyRecurcive($expected, $array, $resetIndex = true)
    {
        $this->assertEquals($expected, Arr::removeEmptyRecurcive($array, $resetIndex));
    }

    public function removeEmptyRecurciveProvider(): array
    {
        return [
            'empty_array' => [
                [],
                []
            ],
            'null' => [
                null,
                null
            ],
            'simple_removing' => [
                [1, -2, 'a3', '4'],
                [1, -2, 'a3', '4', 0, '', null]
            ],
            'simple_removing_with_indexes' => [
                [1, -2],
                [1, -2, 'a3' => null, null, 0, '', []]
            ],
            'simple_removing_and_changing_indexes' => [
                [1, -2, 'a3' => [1, 2, 3]],
                [1, -2, 'a3' => [1, 2, 3], null]
            ],
            'simple_recursive_removing' => [
                [1, -2, 'a3' => [1, 2, 'a3.3' => [1, 2, 3]]],
                [
                    1,
                    -2,
                    'a3' => [
                        1,
                        2,
                        'a3.3' => [
                            1,
                            2,
                            3,
                            4 => [],
                        ]
                    ],
                    null
                ]
            ],
            'deep_recursive_removing_without_reset_index' => [
                [
                    1 => [
                        1,
                        2,
                        3 => [1, 2, 3, 4],
                        4 => 4,
                    ],
                    2 => -2,
                    'a3' => [
                        1,
                        2,
                        'a3.3' => [
                            0 => 1,
                            1 => 2,
                            2 => 3,
                            4 => [
                                1,
                                2,
                                3,
                                4 => [
                                   1 => 2,
                                   2 => true,
                                   4 => ' ',
                                   12 => [1, 2],
                                ]
                            ],
                        ]
                    ],
                ],
                [
                    1 => [
                        1,
                        2,
                        3 => [1, 2, 3, 4, [], null],
                        4,
                        '',
                    ],
                    -2,
                    'a3' => [
                        1,
                        2,
                        'a3.3' => [
                            1,
                            2,
                            3,
                            4 => [
                                1,
                                2,
                                3,
                                4 => [
                                   false,
                                   2,
                                   true,
                                   '',
                                   ' ',
                                   null,
                                   12 => [1, 2, 0],
                                ]
                            ],
                        ]
                    ],
                    null,
                    '',
                    0,
                    false,
                ],
                'resetIndex' => false,
            ],
            'deep_recursive_removing_with_reset_index' => [
                [
                    1 => [
                        1,
                        2,
                        2 => [1, 2, 3, 4],
                        3 => 4,
                    ],
                    2 => -2,
                    'a3' => [
                        1,
                        2,
                        'a3.3' => [
                            0 => 1,
                            1 => 2,
                            2 => 3,
                            3 => [
                                1,
                                2,
                                3,
                                3 => [
                                   0 => 2,
                                   1 => true,
                                   2 => ' ',
                                   3 => [1, 2],
                                ]
                            ],
                        ]
                    ],
                ],
                [
                    1 => [
                        1,
                        2,
                        3 => [1, 2, 3, 4, [], null],
                        4,
                        '',
                    ],
                    -2,
                    'a3' => [
                        1,
                        2,
                        'a3.3' => [
                            1,
                            2,
                            3,
                            4 => [
                                1,
                                2,
                                3,
                                4 => [
                                   false,
                                   2,
                                   true,
                                   '',
                                   ' ',
                                   null,
                                   12 => [1, 2, 0],
                                ]
                            ],
                        ]
                    ],
                    null,
                    '',
                    0,
                    false,
                ],
                'resetIndex' => true
            ]
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $array
     *
     * @dataProvider removeNotScalarProvider
     */
    public function testRemoveNotScalar(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::removeNotScalar($array));
    }

    public function removeNotScalarProvider(): array
    {
        return [
            [
                [],
                []
            ],
            [
                [
                    1, 2, 3, 4, 0
                ],
                [
                    1, 2, 3, 4, 0
                ]
            ],
            [
                [
                    1, 2, 3 => '', 6 => false, 7 => 0
                ],
                [
                    1, 2, null, '', [], new stdClass, false, 0
                ]
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $array
     *
     * @dataProvider removeNullProvider
     */
    public function testRemoveNull(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::removeNull($array));
    }

    public function removeNullProvider(): array
    {
        return [
            [
                [],
                []
            ],
            [
                [
                    1, 2, 3, 4, 0
                ],
                [
                    1, 2, 3, 4, 0, null
                ]
            ],
            [
                [
                    1, 2, 3 => '', [], new stdClass, false, 0
                ],
                [
                    1, 2, null, '', [], new stdClass, false, 0
                ]
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $array
     *
     * @dataProvider removeEmptyProvider
     */
    public function testRemoveEmpty(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::removeEmpty($array));
    }

    public function removeEmptyProvider(): array
    {
        return [
            [
                'expected' => [],
                'array' => []
            ],
            [
                'expected' => [
                    1, 2, 3, 4
                ],
                'array' => [
                    1, 2, 3, 4, 0
                ]
            ],
            [
                'expected' => [
                    1, 2, 5 => new stdClass
                ],
                'array' => [
                    1, 2, null, '', [], new stdClass, false, 0
                ]
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $array
     *
     * @dataProvider trimProvider
     */
    public function testTrim(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::trim($array));
    }

    public function trimProvider(): array
    {
        return [
            [
                [],
                []
            ],
            [
                'expected' => [
                    1, 2, 3, 4, '', '', '', 'a', 'array', '', [],
                    null, new stdClass, false,
                ],
                'array' => [
                    1, 2, 3, 4, '', ' ', '    ', 'a', '        array     ', "\n", [],
                    null, new stdClass, false,
                ]
            ],
        ];
    }

    /**
     * @param  mixed  $expected
     * @param  array  $array
     *
     * @dataProvider lastProvider
     */
    public function testLast($expected = null, array $array)
    {
        $this->assertSame($expected, Arr::last($array));
    }

    public function lastProvider(): array
    {
        return [
            [
                'expected' => 5,
                'array' => [
                    1, 2, 3, 4, 5
                ],
            ],
            [
                'expected' => 6,
                'array' => [
                    5 => 1, 2, 3, 4, 5, 6
                ],
            ],
            [
                null,
                []
            ],
            [
                null,
                [null]
            ],
            [
                0,
                [0]
            ]
        ];
    }

    public function testLastWithOperations()
    {
        $array = [5 => 1, 2, 3, 4, 5, 6];
        $this->assertSame(6, Arr::last($array));
        $this->assertEquals(1, current($array)); // The index not changed

        next($array);
        $this->assertEquals(2, current($array));
        $this->assertEquals(Arr::last($array), 6); // The index not changed
        $this->assertEquals(2, current($array));

        end($array);
        $this->assertEquals(6, current($array));
        $this->assertEquals(Arr::last($array), 6); // The index not changed

        unset($array[key($array)]); // Removes the last element
        $this->assertEquals(Arr::last($array), 5);
    }

    /**
     * @param array $expected
     * @param array $array
     * @param array $indexes
     *
     * @dataProvider unsetProvider
     */
    public function testUnset(array $expected, array $array, array $indexes)
    {
        $this->assertEquals($expected, Arr::unset($array, ...$indexes));
    }

    public function unsetProvider(): array
    {
        return [
            [
                'expected' => [],
                'array' => [],
                'indexes' => [10],
            ],
            [
                'expected' => [1, 2, 3],
                'array' => [1, 2, 3],
                'indexes' => [[]],
            ],
            [
                'expected' => [],
                'array' => [],
                'indexes' => [null],
            ],
            [
                'expected' => [1, 2, 3],
                'array' => [1, 2, 3],
                'indexes' => [null],
            ],
            [
                'expected' => [1, 'null' => 2, 3],
                'array' => [1, 'null' => 2, 3],
                'indexes' => [null],
            ],
            [
                'expected' => [
                    1 => 2, 3, 4, 5, 6,
                    'key1' => '1',
                    'key2' => 2,
                    9 => 10,
                    10 => 11,
                    100,
                    120,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    99,
                    23,
                    45,
                    56,
                    67
                ],
                'array' => [
                    0, 2, 3, 4, 5, 6,
                    'key1' => '1',
                    'key2' => 2,
                    9 => 10,
                    10 => 11,
                    100,
                    120,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    99,
                    23,
                    45,
                    56,
                    67
                ],
                'indexes' => [0],
            ],
            [
                'expected' => [
                    1 => 2, 3 => 4, 5 => 6,
                    'key2' => 2,
                    9 => 10,
                    10 => 11,
                    100,
                    120,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    99,
                    23,
                    45,
                    56,
                    67
                ],
                'array' => [
                    1 => 2, 3, 4, 5, 6,
                    'key1' => '1',
                    'key2' => 2,
                    9 => 10,
                    10 => 11,
                    100,
                    120,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    99,
                    23,
                    45,
                    56,
                    67
                ],
                'indexes' => [2, 4, 'key1'],
            ],
            [
                'expected' => [
                    1 => 2, 3 => 4, 5 => 6,
                    9 => 10,
                    11 => 100,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    13 => 99,
                    23,
                    45,
                    56,
                    67
                ],
                'array' => [
                    1 => 2, 3 => 4, 5 => 6,
                    'key2' => 2,
                    9 => 10,
                    10 => 11,
                    100,
                    120,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    99,
                    23,
                    45,
                    56,
                    67
                ],
                'indexes' => ['key2', 10, 12, 20,30],
            ],
            [
                'expected' => [
                    1 => 2, 3 => 4, 5 => 6,
                    9 => 10,
                    11 => 100,
                    13 => 99,
                    23,
                    17 => 67
                ],
                'array' => [
                    1 => 2, 3 => 4, 5 => 6,
                    9 => 10,
                    11 => 100,
                    'key3' => 3,
                    'key4' => 4,
                    'key5' => 5,
                    13 => 99,
                    23,
                    45,
                    56,
                    67
                ],
                'indexes' => [
                    ['key2', 'key4'], ['key3', 'key5', 15, 16], []
                ],
            ],
            [
                'expected' => [
                    9 => 10,
                    13 => 99,
                    17 => 67
                ],
                'array' => [
                    1 => 2, 3 => 4, 5 => 6,
                    9 => 10,
                    11 => 100,
                    13 => 99,
                    23,
                    17 => 67
                ],
                'indexes' => [
                    ['key2', 'key4'], ['key3', 'key5', 15, 16], [[1, 3, 5, 14, [11]]]
                ],
            ],
        ];
    }

    /**
     * @param  mixed $expected
     * @param  array $values
     *
     * @dataProvider firstExistsProvider
     */
    public function testFirstExists($expected = null, array $values)
    {
        $this->assertSame($expected, Arr::firstExists(...$values));
    }

    public function firstExistsProvider(): array
    {
        return [
            [
                'expected' => [],
                'values' => [[]]
            ],
            [
                'expected' => null,
                'values' => [null]
            ],
            [
                'expected' => null,
                'values' => [null, null]
            ],
            [
                'expected' => [],
                'values' => [null, [], null]
            ],
            [
                'expected' => 'value',
                'values' => ['value', [], null]
            ],
            [
                'expected' => [],
                'values' => [null, [], 'value']
            ],
            [
                'expected' => false,
                'values' => [null, false, [], 'value']
            ],
            [
                'expected' => 'value',
                'values' => [null, 'value', [], true]
            ],
            [
                'expected' => null,
                'values' => []
            ],
        ];
    }

    /**
     * @param  mixed $expected
     * @param  mixed ...$values
     *
     * @dataProvider firstNotEmptyProvider
     */
    public function testFirstNotEmpty($expected = null, array $values)
    {
        $this->assertSame($expected, Arr::firstNotEmpty(...$values));
    }

    public function firstNotEmptyProvider(): array
    {
        return [
            [
                'expected' => null,
                'values' => [[]]
            ],
            [
                'expected' => null,
                'values' => [null]
            ],
            [
                'expected' => null,
                'values' => [null, null]
            ],
            [
                'expected' => null,
                'values' => []
            ],
            [
                'expected' => null,
                'values' => [null, [], null]
            ],
            [
                'expected' => 'value',
                'values' => ['value', [], null]
            ],
            [
                'expected' => 'value',
                'values' => [null, [], 'value']
            ],
            [
                'expected' => 'value',
                'values' => [null, false, [], 'value']
            ],
            [
                'expected' => 'value',
                'values' => [null, 'value', [], true]
            ],
            [
                'expected' => true,
                'values' => [false, 0, '', true]
            ],
        ];
    }

    /**
     * @param array $expected
     * @param array $array
     * @param array $keys
     *
     * @dataProvider unsetByReferenceProvider
     */
    public function testUnsetByReference(array $expected, array $array, array $keys)
    {
        Arr::unsetByReference($array, ...$keys);

        $this->assertEquals($expected, $array);
    }

    public function unsetByReferenceProvider(): array
    {
        return [
            [
                [],
                [],
                [
                    0, 2, 'six', 'seven', 'unknown'
                ]
            ],
            [
                [
                    1 => 2, 3 => 4, 5, 8, 9, 'ten' => 10, 11
                ],
                [
                    1, 2, 3, 4, 5, 'six' => 6, 'seven' => 7, 8, 9, 'ten' => 10, 11
                ],
                [
                    0, 2, 'six', 'seven', 'unknown'
                ]
            ],
            [
                [
                    1 => 2, 3 => 4, 5, 8, 9, 'ten' => 10, 11,
                ],
                [
                    1, 2, 3, 4, 5, 'six' => 6, 'seven' => 7, 8, 9, 'ten' => 10, 11, 12, 13
                ],
                [
                    [0, 2], ['six', 'seven', 'unknown'], [8, 9],
                ]
            ]
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $array
     *
     * @dataProvider getValuesUsingIntKeysProvider
     */
    public function testGetValuesUsingIntKeys(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::getValuesUsingIntKeys($array));
    }

    public function getValuesUsingIntKeysProvider(): array
    {
        return [
            [
                [],
                []
            ],
            [
                [
                    'test',
                    'test1',
                    'test2',
                    'test 5',
                    'test1',
                    'test 10',
                    'test array',
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ]
            ]
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $array
     *
     * @dataProvider getValuesUsingStringKeysProvider
     */
    public function testGetValuesUsingStringKeys(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::getValuesUsingStringKeys($array));
    }

    public function getValuesUsingStringKeysProvider(): array
    {
        return [
            [
                [],
                []
            ],
            [
                [
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ]
            ]
        ];
    }

    /**
     * @param array $expected
     * @param array $array
     *
     * @dataProvider resetIntKeysProvider
     */
    public function testResetIntKeys(array $expected, array $array)
    {
        $this->assertEquals($expected, Arr::resetIntKeys($array));
    }

    public function resetIntKeysProvider(): array
    {
        return [
            'empty' => [
                [], []
            ],
            'integer' => [
                [
                    'val1',
                    'val2',
                    'val3',
                    4,
                    5,
                    6,
                    null,
                    false
                ],
                [
                    'val1',
                    'val2',
                    'val3',
                    5 => 4,
                    5,
                    10 => 6,
                    null,
                    false
                ]
            ],
            'string' => [
                [
                    'v1' => 'val1',
                    'v2' => 'val2',
                    'v3' => 'val3',
                    'v4' => 4,
                    'v5' => null,
                    'v6' => false
                ],
                [
                    'v1' => 'val1',
                    'v2' => 'val2',
                    'v3' => 'val3',
                    'v4' => 4,
                    'v5' => null,
                    'v6' => false
                ]
            ],
            'both' => [
                [
                    'v1' => 'val1',
                    'v2' => 'val2',
                    'v3' => 'val3',
                    'v4' => 4,
                    'v5' => null,
                    'v6' => false,
                    'val1',
                    'val2',
                    'val3',
                    4,
                    5,
                    6,
                    null,
                    false
                ],
                [
                    'v1' => 'val1',
                    'v2' => 'val2',
                    'v3' => 'val3',
                    'v4' => 4,
                    'v5' => null,
                    'v6' => false,
                    'val1',
                    'val2',
                    'val3',
                    5 => 4,
                    5,
                    10 => 6,
                    null,
                    false
                ]
            ]
        ];
    }

    /**
     * @param  int|string|null $expected
     * @param  array           $keys
     * @param  string          $key
     *
     * @dataProvider findFirstIndexByKeyProvider
     */
    public function testFindFirstIndexByKey($expected, array $keys, string $key)
    {
        $this->assertEquals($expected, Arr::findFirstIndexByKey($keys, $key));
    }

    public function findFirstIndexByKeyProvider(): array
    {
        return [
            'empty' => [
                null,
                [],
                'key'
            ],
            'no_key' => [
                null,
                [
                    'key1',
                    'key2',
                    function () {},
                    'key3' => 'value',
                    'key4' => 'value 2',
                    10 => 'key5',
                ],
                'key10'
            ],
            'first_key' => [
                0,
                [
                    'key1',
                    'key2',
                    'key3' => 'value',
                    'key4' => 'value 2',
                    10 => 'key5',
                    'key1',
                ],
                'key1'
            ],
            'key5' => [
                10,
                [
                    'key1',
                    'key2',
                    'key3' => 'value',
                    'key4' => 'value 2',
                    10 => 'key5',
                    'key1',
                    'key5'
                ],
                'key5'
            ],
            'key4' => [
                'key4',
                [
                    'key1',
                    'key2',
                    'key3' => 'value',
                    'key4' => 'value 2',
                    10 => 'key5',
                    'key1',
                    'key5'
                ],
                'key4'
            ],
        ];
    }

    /**
     * @param  string|null $expected
     * @param  int|string  $key
     * @param  mixed       $value
     *
     * @dataProvider getKeyFromPairOfValuesProvider
     */
    public function testGetKeyFromPairOfValues($expected, $key, $value, $intKey)
    {
        $this->assertEquals($expected, Arr::getKeyFromPairOfValues($key, $value, $intKey));
    }

    public function getKeyFromPairOfValuesProvider(): array
    {
        return [
            'null' => [
                null,
                function () {},
                function () {},
                true
            ],
            'first_key' => [
                'key',
                'key',
                'value',
                true
            ],
            'key_is_int' => [
                1,
                1,
                'value',
                true
            ],
            'value' => [
                'value',
                1,
                'value',
                false
            ],
            'function' => [
                null,
                1,
                function () {},
                false
            ],
            'key_is_int_value_is_function' => [
                1,
                1,
                function () {},
                true
            ],
            'key_as_null' => [
                'value',
                null,
                'value',
                false
            ],
            'int' => [
                null,
                0,
                1,
                false
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $keys
     * @param  bool   $rewrite
     *
     * @dataProvider uniteKeysProvider
     */
    public function testUniteKeys(array $expected, array $keys, bool $rewrite)
    {
        $this->assertEquals($expected, Arr::uniteKeys($keys, $rewrite));
    }

    public function uniteKeysProvider(): array
    {
        return [
            'empty' => [
                [],
                [],
                true
            ],
            'without_changes' => [
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    'test 5'
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5'
                ],
                true
            ],
            'rewrite' => [
                [
                    'test',
                    'test function' => function () {},
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test1',
                    'test 10',
                    'test array',
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ],
                true
            ],
            'not-rewrite' => [
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    'test 5',
                    'test 10',
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ],
                false
            ]
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $source
     * @param  array  $array
     * @param  bool   $replace
     *
     * @dataProvider mergeKeysProvider
     */
    public function testMergeKeys(array $expected, array $source, array $array, bool $replace)
    {
        $this->assertEquals($expected, Arr::mergeKeys($source, $array, $replace));
    }

    public function mergeKeysProvider(): array
    {
        return [
            [
                [], [], [], true
            ],
            'not_changed' => [
                [
                    'test',
                    'test function' => function () {},
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                    'item5'
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ],
                [
                    'item5',
                ],
                true
            ],
            'changed' => [
                [
                    'test',
                    'test function' => function () {},
                    'test2' => 'Value 2',
                    'test 10',
                    'test 5',
                    'test1' => function () {},
                    'test array' => [3, 2, 1],
                    'new item' => 'value',
                    'item5',
                    'item6'
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ],
                [
                    'test 5',
                    'test1' => function () {},
                    'test array' => [3, 2, 1],
                    'new item' => 'value',
                    'item5',
                    'item6'
                ],
                true
            ],
            'in_empty_array' => [
                [
                    'test 5',
                    'test array' => [3, 2, 1],
                    'new item' => 'value',
                    'item6',
                    'item5' => 'value',
                    'test1'
                ],
                [],
                [
                    'test 5',
                    'test1' => function () {},
                    'test array' => [3, 2, 1],
                    'new item' => 'value',
                    'item5',
                    'item6',
                    'item5' => 'value',
                    'test1'
                ],
                true
            ],
            'not_replaced' => [
                [
                    'test',
                    'test function' => function () {},
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                    'new item' => 'value',
                    'item5',
                    'item6'
                ],
                [
                    'test',
                    'test1',
                    'test2',
                    'test array' => [1, 2, 3],
                    'test function' => function () {},
                    10 => 'test 5',
                    'test1',
                    'test2' => 'Value 2',
                    'test 5' => 'Text Five',
                    'test 10',
                    'test array',
                ],
                [
                    'test 5',
                    'test1' => function () {},
                    'test array' => [3, 2, 1],
                    'new item' => 'value',
                    'item5',
                    'item6'
                ],
                false
            ]
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $leftArray
     * @param  array  $rightArray
     *
     * @dataProvider intersectionProvider
     */
    public function testIntersection(array $expected, array $leftArray, array $rightArray)
    {
        $this->assertEquals($expected, Arr::intersection($leftArray, $rightArray));
    }

    public function intersectionProvider(): array
    {
        return [
            [
                [], [], []
            ],
            [
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ]
            ],
            [
                [
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 4,
                        'b' => 4,
                        'c' => 4,
                    ],
                ]
            ],
            [
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ],
            [
                [],
                [],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $leftArray
     * @param  array  $rightArray
     *
     * @dataProvider leftDivergenceProvider
     */
    public function testLeftDivergence(array $expected, array $leftArray, array $rightArray)
    {
        $this->assertEquals($expected, Arr::leftDivergence($leftArray, $rightArray));
    }

    public function leftDivergenceProvider(): array
    {
        return [
            [
                [], [], []
            ],
            [
                [
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ]
            ],
            [
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 4,
                        'b' => 4,
                        'c' => 4,
                    ],
                ]
            ],
            [
                [
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ],
            [
                [],
                [],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $leftArray
     * @param  array  $rightArray
     *
     * @dataProvider rightDivergenceProvider
     */
    public function testRightDivergence(array $expected, array $leftArray, array $rightArray)
    {
        $this->assertEquals($expected, Arr::rightDivergence($leftArray, $rightArray));
    }

    public function rightDivergenceProvider(): array
    {
        return [
            [
                [], [], []
            ],
            [
                [],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ]
            ],
            [
                [
                    [
                        'a' => 4,
                        'b' => 4,
                        'c' => 4,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 4,
                        'b' => 4,
                        'c' => 4,
                    ],
                ]
            ],
            [
                [
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ],
            [
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ],
                [],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ],
        ];
    }

    /**
     * @param  array  $expected
     * @param  array  $leftArray
     * @param  array  $rightArray
     *
     * @dataProvider differenceProvider
     */
    public function testDifference(array $expected, array $leftArray, array $rightArray)
    {
        $this->assertEquals($expected, Arr::difference($leftArray, $rightArray));
    }

    public function differenceProvider(): array
    {
        return [
            [
                [
                    'left' => [
                        [
                            'a' => 2,
                            'b' => 2,
                            'c' => 2,
                        ],
                        [
                            'a' => 3,
                            'b' => 3,
                            'c' => 3,
                        ],
                    ],
                    'intersection' => [
                        [
                            'a' => 1,
                            'b' => 1,
                            'c' => 1,
                        ],
                    ],
                    'right' => [
                        [
                            'a' => 2,
                            'b' => 2,
                            'c' => 2,
                            'd' => 2,
                        ],
                        [
                            'a' => 3,
                            'b' => 3,
                            'c' => 3,
                            'd' => 3,
                        ],
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                    ],
                ],
                [
                    [
                        'a' => 1,
                        'b' => 1,
                        'c' => 1,
                    ],
                    [
                        'a' => 2,
                        'b' => 2,
                        'c' => 2,
                        'd' => 2,
                    ],
                    [
                        'a' => 3,
                        'b' => 3,
                        'c' => 3,
                        'd' => 3,
                    ],
                ]
            ]
        ];
    }
}
