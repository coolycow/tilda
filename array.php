<?php
    class RandomUniqueMatrix
    {
        private int $min;
        private int $max;

        private int $rowsCount;
        private int $colsCount;

        private array $array = [];
        
        public function __construct(int $rowsCount, int $colsCount, int $min, int $max)
        {
            $this->min = abs(min($min, $max));
            $this->max = abs(max($min, $max));

            $this->rowsCount = $rowsCount;
            $this->colsCount = $colsCount;

            // Проверяем возможность заполнения.
            $size = $this->rowsCount * $this->colsCount ;

            if ($size - 1 > $this->max - $this->min) {
                throw new Exception("Размер матрицы ($size) не позволит заполнить её уникальными числами (от $this->min до $this->max).");
            }

            // Генерируем матрицу.
            $this->generate();
        }

        /**
         * Генерация матрицы.
         */
        private function generate(): void
        {
            $uniqueValues = [];
            $this->array = [];

            for ($row = 0; $row < $this->rowsCount; $row++) {
                $newRow = [];
        
                for ($col = 0; $col < $this->colsCount; $col++) {
                    while(($value = rand($this->min, $this->max)) && in_array($value, $uniqueValues));
                    $uniqueValues[] = $newRow[] = $value;
                }
        
                $this->array[] = $newRow;
            }
        }

        /**
         * Выводит массив.
         */
        public function printArray(): void
        {
            echo "Array:\n";

            foreach ($this->array as $item) {
                echo implode(', ', $item) . "\n";
            }
        }

        /**
         * Вычисляет и выводит сумму по строкам.
         */
        public function printSumRow(): void
        {
            $sumRow = [];

            foreach ($this->array as $row) {
                $sumRow[] = array_sum($row);
            }

            echo "\nSum by rows: " . implode(', ', $sumRow);
        }

        /**
         * Вычисляет и выводит сумму по столбцам.
         */
        public function printSumCol(): void
        {
            $sumCol = array_fill(0, $this->colsCount, 0);

            for ($i = 0; $i < $this->colsCount; $i++) {
                foreach ($this->array as $row) {
                    $sumCol[$i] += $row[$i];
                }  
            }

            echo "\nSum by cols: " . implode(', ', $sumCol);
        }
    }

    try {
        $rowsCount = 5; // Количество строк
        $colsCount = 7; // Количество столбцов
        $min = 1; // Минимальное значение случайного числа
        $max = 1000; // Максимальное значение случайного числа

        $matrix = new RandomUniqueMatrix($rowsCount, $colsCount, $min, $max);

        $matrix->printArray();
        $matrix->printSumRow();
        $matrix->printSumCol();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>