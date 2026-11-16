<?php

namespace App\Services;

use PDO;

class RegionService
{
    protected ?PDO $pdo = null;

    /**
     * Get or create PDO instance for regions SQLite database.
     */
    protected function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $path = database_path('regions.sqlite');
            $this->pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return $this->pdo;
    }

    /**
     * Get all 38 Indonesian provinces.
     *
     * @return array<int, array{code: string, name: string}>
     */
    public function getProvinces(): array
    {
        $stmt = $this->getPdo()->query('SELECT code, name FROM provinces ORDER BY name ASC');

        return $stmt->fetchAll();
    }

    /**
     * Get regencies / cities for a given province.
     *
     * @return array<int, array{code: string, name: string}>
     */
    public function getRegencies(string $provinceCode): array
    {
        $stmt = $this->getPdo()->prepare('SELECT code, name FROM regencies WHERE province_code = ? ORDER BY name ASC');
        $stmt->execute([$provinceCode]);

        return $stmt->fetchAll();
    }

    /**
     * Get districts (kecamatan) for a given regency / city.
     *
     * @return array<int, array{code: string, name: string}>
     */
    public function getDistricts(string $regencyCode): array
    {
        $stmt = $this->getPdo()->prepare('SELECT code, name FROM districts WHERE regency_code = ? ORDER BY name ASC');
        $stmt->execute([$regencyCode]);

        return $stmt->fetchAll();
    }

    /**
     * Get villages (kelurahan / desa) with postal codes for a given district.
     *
     * @return array<int, array{code: string, name: string, postal_code: string|null}>
     */
    public function getVillages(string $districtCode): array
    {
        $stmt = $this->getPdo()->prepare('SELECT code, name, postal_code FROM villages WHERE district_code = ? ORDER BY name ASC');
        $stmt->execute([$districtCode]);

        return $stmt->fetchAll();
    }
}
