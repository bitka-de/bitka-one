<?php

declare(strict_types=1);

class DataLoader
{
  private string $basePath;

  public function __construct(string $basePath = null)
  {
    $this->basePath = $basePath ?? get_template_directory() . '/data/';
  }

  /**
   * Lädt Daten aus einer beliebigen JSON-Datei im data-Verzeichnis.
   *
   * @param string $filename Dateiname der JSON-Datei (z.B. 'support.json')
   * @return array<string, mixed>
   */
  public function loadJson(string $filename): array
  {
    $filePath = rtrim($this->basePath, '/') . '/' . ltrim($filename, '/');
    if (!file_exists($filePath)) {
      return [];
    }
    $json = file_get_contents($filePath);
    if ($json === false) {
      return [];
    }
    $data = json_decode($json, true);
    if (!is_array($data)) {
      return [];
    }
    return $data;
  }
}
