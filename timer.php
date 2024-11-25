<?php
// declare strict types for microoptimisations of typing
declare(strict_types=1);

// Enable JIT compilation with optimal settings
ini_set('opcache.jit', 1255);  // Tracing JIT
ini_set('opcache.jit_buffer_size', '1024M');  // Increased buffer size for better performance

$start = hrtime(true);

const OUTER_LOOP = 10000;
const INNER_LOOP = 100000;

// Pre-calculate the sum for inner loop - it's always the same!
// For sequence 0 to n-1, sum is (n * (n-1)) / 2
$innerLoopSum = (INNER_LOOP * (INNER_LOOP - 1)) / 2;

// Use SplFixedArray for better memory efficiency
$array = new SplFixedArray(OUTER_LOOP);

// Single loop with mathematical optimization
for ($i = 0; $i < OUTER_LOOP; $i++) {
    $array[$i] = $innerLoopSum;
}

$end = hrtime(true);
$difference = ($end - $start) / 1e9;

echo 'Optimised Run: ' . $difference . " seconds\n";

$start = hrtime(true);

$secondArray = array_fill(0, 10000, 0);

for ($i = 0; $i < 10000; $i++) {
    for ($j = 0; $j < 100000; $j++) {
        $secondArray[$i] += $j;
    }
}

$end = hrtime(true);
$differenceTwo = ($end - $start) / 1e9;

echo 'Unoptimised Run: ' . $differenceTwo . " seconds\n";

// Compare results
$areEqual = true;
$firstDifference = null;

$arrayLength = count($secondArray);

for ($i = 0; $i < $arrayLength; $i++) {
    if ($secondArray[$i] !== $array[$i]) {
        $areEqual = false;
        $firstDifference = [
            'index' => $i,
            'original' => $secondArray[$i],
            'optimized' => $array[$i]
        ];
        break;
    }
}

// Output results
if ($areEqual) {
    echo "Results are strictly equal!\n";
} else {
    echo "Results differ at:\n";
    echo "Index: " . $firstDifference['index'] . "\n";
    echo "Original value: " . $firstDifference['original'] . "\n";
    echo "Optimized value: " . $firstDifference['optimized'] . "\n";
}
