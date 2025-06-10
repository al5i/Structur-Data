<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildFileTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:build-file-tree';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build a tree structure from file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = config('workers.file');

        if (!file_exists($filePath)) {
            $this->error('File does not exist: ' . $filePath);
            return;
        }

        $fileses = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $fileName = pathinfo($filePath, PATHINFO_FILENAME);

        $tree = [];
        foreach ($fileses as $file) {
            $tree[] = $fileName. $file;
        }

        $this->displayTree($tree);
    }

    private function displayTree(array $tree)
    {
        $result = [];
        $references = [];

        foreach ($tree as $item) {
            $parts = explode('_', $item);
            $baseName = $parts[0];

            // Если корневой элемент не существует
            if (!isset($references[$baseName])) {
                $result[] = [
                    'name' => $baseName,
                    'children' => []
                ];
                $references[$baseName] = &$result[count($result) - 1]['children'];
            }

            // Если дочерние элементы имеются
            if (count($parts) > 1) {
                $currentRef = &$references[$baseName];
                $currentPath = $baseName;

                for ($i = 1; $i < count($parts); $i++) {
                    $currentPath .= '_' . $parts[$i];
                    $childName = $baseName;
                    for ($j = 1; $j <= $i; $j++) {
                        $childName .= '_' . $parts[$j];
                    }

                    // Поиск дочерного элемент
                    $found = false;
                    foreach ($currentRef as &$child) {
                        if ($child['name'] === $childName) {
                            $currentRef = &$child['children'];
                            $found = true;
                            break;
                        }
                    }

                    // Если не найден, создаем новый
                    if (!$found) {
                        $currentRef[] = [
                            'name' => $childName,
                            'children' => []
                        ];
                        $lastIndex = count($currentRef) - 1;
                        $currentRef = &$currentRef[$lastIndex]['children'];
                    }

                    $references[$currentPath] = &$currentRef;
                }
            }
        }

        print_r($result);
    }
}
