<?php

namespace Calculator\Tester;

use \DirectoryIterator;

class Tester
{
	private string $directory;

	public function getDirectory(): string
	{
		return $this->directory;
	}

	public function __construct(string $directory)
	{
		$this->directory = $directory;
	}

	public function directoryList(string $path): array
	{
		$directories = [];

		foreach(new DirectoryIterator($path . $folder) as $file) {

			$baseName = $file->getBasename();

			if (
				$baseName !== '.' &&
				$baseName !== '..' &&
				$baseName !== 'Assert'
			) {
				array_push($directories, $baseName);
			}

		}

		return $directories;
	}

	public function getTestClasses()
	{
		$classes = [];

		foreach ($this->directoryList($this->getDirectory()) as $folder) {

			$directory = $this->directoryList(
				$this->getDirectory() .
				$folder
			);

			array_push(
				$classes,
				substr($directory[0], 0, -4)
			);

		}

		return $classes;
	}
}